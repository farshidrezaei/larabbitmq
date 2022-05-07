<?php

namespace FarshidRezaei\Larabbitmq\Lib;

use Closure;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    private AMQPStreamConnection $connection;

    private AMQPChannel $channel;

    private string $bind;

    private string $exchange;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection->getConnection();
        if ($this->connection) {
            $this->channel = $this->connection->channel();
        }
    }


    /**
     * Deletes an exchange by its name.
     *
     * @param  string  $name
     * @param  bool  $ifUnused
     * @param  bool  $nowait
     * @param  int|null  $ticket
     *
     * @return bool
     */
    public function deleteExchange(
        string $name,
        bool $ifUnused = false,
        bool $nowait = false,
        int $ticket = null
    ): bool {
        $this->channel->exchange_delete($name, $ifUnused, $nowait, $ticket);

        return true;
    }

    /**
     * Deletes a queue by it's name.
     *
     * @param  string  $name
     * @param  bool  $ifUnused
     * @param  bool  $ifEmpty
     * @param  bool  $nowait
     * @param  int|null  $ticket
     *
     * @return bool
     */
    public function deleteQueue(
        string $name,
        bool $ifUnused = false,
        bool $ifEmpty = false,
        bool $nowait = false,
        int $ticket = null
    ): bool {
        $this->channel->queue_delete($name, $ifUnused, $ifEmpty, $nowait, $ticket);

        return true;
    }

    private function onQueue(string $queue): self
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        return $this;
    }

    private function onExchange(string $exchange): self
    {
        $this->channel->exchange_declare($exchange, 'direct', false, true, false);

        return $this;
    }

    private function bind($queue, $exchange): self
    {
        $this->channel->queue_bind($queue, $exchange, $this->bind);

        return $this;
    }

    private function send(string $message): void
    {
        $message = new AMQPMessage($message);
        $this->channel->basic_publish($message, $this->exchange, $this->bind);
    }

    private function get(string $queue, Closure $callback): void
    {
        $this->channel->basic_consume($queue, '', false, false, false, false, $callback);
    }

    /**
     * @throws Exception
     */
    public function publish(string $queueName, string $exchangeName, $message): void
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }

        if ($this->connection) {
            $this->bind = "{$queueName}_{$exchangeName}";
            $this->exchange = $exchangeName;
            $this->onExchange($exchangeName)
                ->onQueue($queueName)
                ->bind($queueName, $exchangeName)
                ->send($message);
        }
    }

    /**
     * @throws Exception
     */
    public function consume(string $queueName, Closure $callback): void
    {
        if ($this->connection) {
            $this->onQueue($queueName)->get($queueName, $callback);
            while ($this->channel->is_consuming()) {
                $this->channel->wait();
            }
        }
        $this->channel->close();
        $this->connection->close();
    }
}

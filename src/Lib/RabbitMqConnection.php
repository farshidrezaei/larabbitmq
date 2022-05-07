<?php

namespace FarshidRezaei\Larabbitmq\Lib;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMqConnection
{
    public AMQPStreamConnection $connection;

    public function __construct()
    {
        if (config('larabbitmq.connection.rabbitmq.host')) {
            $this->setConnection(
                config('larabbitmq.connection.rabbitmq.host'),
                config('larabbitmq.connection.rabbitmq.port'),
                config('larabbitmq.connection.rabbitmq.user'),
                config('larabbitmq.connection.rabbitmq.pass')
            );
        }
    }

    /**
     * Get the connection.
     *
     * @return AMQPStreamConnection|null
     */
    public function getConnection(): ?AMQPStreamConnection
    {
        if (config('larabbitmq.connection.rabbitmq.host')) {
            return $this->connection;
        }
        return null;
    }

    /**
     * Set the rabbitmq connection.
     *
     * @param  string  $host
     * @param  string  $port
     * @param  string  $user
     * @param  string  $password
     * @param  string  $vhost
     * @param  bool  $insist
     * @param  string  $login_method
     * @param  null  $login_response  @deprecated
     * @param  string  $locale
     * @param  float  $connection_timeout
     * @param  float  $read_write_timeout
     * @param  null  $context
     * @param  bool  $keepalive
     * @param  int  $heartbeat
     * @param  float  $channel_rpc_timeout
     * @param  string|null  $ssl_protocol
     */
    public function setConnection(
        string $host,
        string $port,
        string $user,
        string $password,
        string $vhost = '/',
        bool $insist = false,
        string $login_method = 'AMQPLAIN',
        $login_response = null,
        string $locale = 'en_US',
        float $connection_timeout = 3.0,
        float $read_write_timeout = 3.0,
        $context = null,
        bool $keepalive = false,
        int $heartbeat = 0,
        float $channel_rpc_timeout = 0.0,
        string $ssl_protocol = null
    ): void {
        $this->connection = new AMQPStreamConnection(
            $host,
            $port,
            $user,
            $password,
            $vhost,
            $insist,
            $login_method,
            $login_response,
            $locale,
            $connection_timeout,
            $read_write_timeout,
            $context,
            $keepalive,
            $heartbeat,
            $channel_rpc_timeout,
            $ssl_protocol
        );
    }
}


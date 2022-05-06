<?php

namespace FarshidRezaei\Larabbitmq\Commands;


use FarshidRezaei\Larabbitmq\Facades\Larabbitmq;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbitmq:consume {--queue=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume Specified rabbitMq queue';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (!($queue = $this->option('queue'))) {
            $this->error("queue option is required --queue=default");
            return CommandAlias::FAILURE;
        }
        $this->info("Starting Consume rabbitMq on \"$queue\" queue. use CTRL+C to cancel process.");

        $callback = function (AMQPMessage $message) use ($queue) {
            if (array_key_exists($queue, $handlers = config('larabbitmq.consume-handlers'))) {
                $data = json_decode($message->body, true);
                try {
                    App::make($handlers[$queue])->handle($data);
                    $message->ack();
                } catch (\Exception $exception) {
                    $message->nack(true);
                }
            }
        };

        Larabbitmq::consume($queue, $callback);


        return CommandAlias::SUCCESS;
    }
}

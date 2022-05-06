<?php

namespace FarshidRezaei\Larabbitmq\Providers;

use FarshidRezaei\Larabbitmq\Commands\ConsumeCommand;
use FarshidRezaei\Larabbitmq\Commands\MakeConsumeHandlerCommand;
use FarshidRezaei\Larabbitmq\Lib\RabbitMQ;
use FarshidRezaei\Larabbitmq\Lib\RabbitMqConnection;
use Illuminate\Support\ServiceProvider;

class LarabbitmqServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            MakeConsumeHandlerCommand::class,
            ConsumeCommand::class
        ]);

        foreach (glob(__DIR__.'/../Helpers'.'/*.php') as $file) {
            require_once $file;
        }

        $this->mergeConfigFrom(__DIR__.'/../Configs/config.php', 'larabbitmq');

        $this->app->singleton('rabbitmqConnection', fn() => (new RabbitMqConnection())->getConnection());

        $this->app->bind('rabbitmq', fn() => (new RabbitMQ($this->app->make('rabbitmqConnection'))));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__.'/../Configs/config.php' => config_path('larabbitmq.php'),
                ],
                'config'
            );
        }
    }
}

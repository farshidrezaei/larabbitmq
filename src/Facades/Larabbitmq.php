<?php

namespace FarshidRezaei\Larabbitmq\Facades;

use Closure;
use FarshidRezaei\Larabbitmq\Lib\RabbitMQ;
use Illuminate\Support\Facades\Facade;

/**
 * @method static int|null publish(string $queue, string $exchange, string|array $message)
 * @method static int|null consume(string $queue, Closure $callback)
 *
 * @see RabbitMQ
 */
class Larabbitmq extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'rabbitmq';
    }
}

# Larabbitmq, RabbitMq Integration for Laravel

## Installation

### composer

```bash
composer require farshidrezaei/larabbitmq
```

## Larabbitmq environment

Set bellow config to `.env` file with your installed rabbitmq credential 

```dotenv  
LARABBITMQ_RABBIT_HOST=
LARABBITMQ_RABBIT_PORT=
LARABBITMQ_RABBIT_USERNAME=
LARABBITMQ_RABBIT_PASSWORD=
```  

## Config

Call bellow command to publish config file:

```bash
php artisan vendor:publish --provider="FarshidRezaei\Larabbitmq\Providers\LarabbitmqServiceProvider" --tag="config"
```

## Usage:

### publish:

You can use Larabbitmq Facade easily to publish messages on specified queue.

```php
use FarshidRezaei\Larabbitmq\Facades\Larabbitmq;

Larabbitmq::publish( 'default_queue','default_exchange','simple_text_message' );
 ```

-----------------


### consume:

For consuming a queue you should create consume handler class and specified it for a queue in `config/larabbitmq.php` .

call bellow command to create new consume handler class:
```bash
php artisan larabbitmq:make-consume-handler ExampleConsumeHandler
```
Then ExampleConsumeHandler.php class will create in app/LarabbitmqConsumeHandlers.
you must add this to config/larabbitmq.php like this:
```php
// config/larabbitmq.php
  
  // ...
  
  'consume-handlers' => [
    'default_queue'=>App\LarabbitmqConsumeHandlers\ExampleConsumeHandler::class
  ]
    
  // ...
```

Now you can call bellow command to consume specifed queue:
```php
php artisan larabbitmq:consume --queue=default_queue
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

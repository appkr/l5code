<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class CustomLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $handler = new StreamHandler('php://stdout');
        /** @var \Illuminate\Log\Writer $logger */
        $logger = $this->app->make(LoggerInterface::class);
        /** @var \Monolog\Logger $monolog */
        $monolog = $logger->getMonolog();
        $monolog->pushHandler($handler);
    }
}

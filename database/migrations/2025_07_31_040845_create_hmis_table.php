<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hmis', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->string('hostname');
            $table->string('ip_address');
            $table->string('mac_address');
            $table->string('serial_number');
            $table->string('model_type');
            $table->string('os');
            $table->string('ram');
            $table->string('ssd_hdd');
            $table->string('pc_username');
            $table->string('password');
            $table->string('monitor_size');
            $table->string('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hmis');
    }
};

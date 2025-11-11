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
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('title', 'tipe');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->string('title')->after('tipe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('title');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('tipe', 'title');
        });
    }
};

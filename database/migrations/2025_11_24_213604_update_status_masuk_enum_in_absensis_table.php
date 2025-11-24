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
        Schema::table('absensis', function (Blueprint $table) {
            $table->enum('status_masuk', ['hadir', 'terlambat', 'tidak_hadir', 'izin', 'sakit', 'alfa'])->default('tidak_hadir')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->enum('status_masuk', ['hadir', 'terlambat', 'tidak_hadir'])->default('tidak_hadir')->change();
        });
    }
};

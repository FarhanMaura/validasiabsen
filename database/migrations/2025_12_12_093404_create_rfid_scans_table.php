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
        Schema::create('rfid_scans', function (Blueprint $table) {
            $table->id();
            $table->string('rfid_uid', 50);
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null');
            $table->enum('status', ['registered', 'unregistered'])->default('unregistered');
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();
            
            // Index for performance
            $table->index('scanned_at');
            $table->index(['rfid_uid', 'scanned_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_scans');
    }
};

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
        Schema::create('riwayat_moves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->string('from');
            $table->string('to');
            $table->string('pic'); // 🔥 WAJIB
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_moves');
    }
};

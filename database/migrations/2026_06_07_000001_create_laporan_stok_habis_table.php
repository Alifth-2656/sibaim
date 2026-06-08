<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_stok_habis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->string('pic');
            $table->string('commodity');
            $table->integer('qty_diminta');
            $table->enum('type', ['stok_habis', 'tidak_ditemukan'])->default('stok_habis');
            $table->enum('status', ['pending', 'ditangani'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_stok_habis');
    }
};

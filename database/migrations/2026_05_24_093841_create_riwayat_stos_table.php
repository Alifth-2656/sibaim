<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_stos', function (Blueprint $table) {
            $table->id();
            $table->string('pic');
            $table->date('tanggal');
            $table->integer('total_item');
            $table->integer('total_match');
            $table->integer('total_selisih');
            $table->timestamps();
        });

        Schema::create('riwayat_sto_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riwayat_sto_id')->constrained('riwayat_stos')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('qty_sistem');   // qty sebelum STO
            $table->integer('qty_fisik');    // qty yang diinput user
            $table->integer('selisih');      // qty_fisik - qty_sistem
            $table->boolean('is_adjusted')->default(false); // apakah qty DB diubah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_sto_details');
        Schema::dropIfExists('riwayat_stos');
    }
};
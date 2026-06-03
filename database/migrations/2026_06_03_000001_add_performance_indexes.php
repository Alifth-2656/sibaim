<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index untuk filter created_at (dipakai di whereMonth, whereBetween, subDays)
        Schema::table('riwayat_ins', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('barang_id');
        });

        Schema::table('riwayat_outs', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('barang_id');
        });

        Schema::table('riwayat_moves', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('barang_id');
        });

        // Index untuk STO filter tanggal
        Schema::table('riwayat_stos', function (Blueprint $table) {
            $table->index('tanggal');
        });

        // Index untuk filter stok (qty <= min)
        Schema::table('barangs', function (Blueprint $table) {
            $table->index('qty');
        });

        // Index untuk permintaan terbaru
        Schema::table('permintaans', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_ins',  fn($t) => $t->dropIndex(['created_at', 'barang_id']));
        Schema::table('riwayat_outs', fn($t) => $t->dropIndex(['created_at', 'barang_id']));
        Schema::table('riwayat_moves',fn($t) => $t->dropIndex(['created_at', 'barang_id']));
        Schema::table('riwayat_stos', fn($t) => $t->dropIndex(['tanggal']));
        Schema::table('barangs',      fn($t) => $t->dropIndex(['qty']));
        Schema::table('permintaans',  fn($t) => $t->dropIndex(['created_at']));
    }
};

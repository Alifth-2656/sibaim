<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->default('permintaan'); // permintaan, info, dll
            $table->foreignId('permintaan_id')->nullable()->constrained('permintaans')->onDelete('cascade');
            $table->json('for_roles'); // ["admin", "improvement"]
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
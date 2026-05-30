<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('lokasi');
            $table->integer('harga');
            $table->string('tipe_kos'); // Menyimpan: Pria, Wanita, atau Campur

            // Kolom Fasilitas (Menggunakan Boolean: 1 jika ada, 0 jika tidak ada)
            $table->boolean('ac')->default(0);
            $table->boolean('wifi')->default(0);
            $table->boolean('kamar_mandi_dalam')->default(0);

            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};

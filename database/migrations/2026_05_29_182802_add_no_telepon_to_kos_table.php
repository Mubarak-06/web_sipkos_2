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
        Schema::table('kos', function (Blueprint $table) {
            $table->text('alamat')->after('lokasi'); // Menambah kolom alamat
            $table->string('no_telepon')->after('deskripsi'); // Menambah kolom no_telepon
            $table->string('foto')->nullable()->after('no_telepon');
            $table->string('foto_2')->nullable()->after('foto');
            $table->string('foto_3')->nullable()->after('foto_2'); // <-- GABUNG DI SINI (nullable agar tidak error jika kos lama belum punya foto)
        });
    }

    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'no_telepon', 'foto', 'foto_2', 'foto_3']); // <-- Tambahkan 'foto' di sini juga
        });
    }
};
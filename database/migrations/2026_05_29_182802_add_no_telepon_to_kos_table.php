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
        });
    }

    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'no_telepon']);
        });
    }
};

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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kos_id')
                ->constrained('kos')
                ->onDelete('cascade');

            $table->string('nama_penyewa')->nullable();

            $table->date('tanggal_checkin');

            $table->integer('durasi');

            $table->boolean('jasa_pindahan')->default(false);

            $table->string('nama_jasa_pindahan')->nullable();
            
            $table->bigInteger('total_harga');

            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'kode_booking')) {
                $table->string('kode_booking')->nullable()->after('id');
            }

            if (!Schema::hasColumn('bookings', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('total_harga');
            }

            if (!Schema::hasColumn('bookings', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('Menunggu Verifikasi Admin')->after('metode_pembayaran');
            }

            if (!Schema::hasColumn('bookings', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'bukti_pembayaran')) {
                $table->dropColumn('bukti_pembayaran');
            }

            if (Schema::hasColumn('bookings', 'status_pembayaran')) {
                $table->dropColumn('status_pembayaran');
            }

            if (Schema::hasColumn('bookings', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }

            if (Schema::hasColumn('bookings', 'kode_booking')) {
                $table->dropColumn('kode_booking');
            }
        });
    }
};
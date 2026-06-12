<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'kode_booking',
        'kos_id',
        'tanggal_checkin',
        'durasi',
        'jasa_pindahan',
        'nama_jasa_pindahan',
        'total_harga',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
        'status',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }
}
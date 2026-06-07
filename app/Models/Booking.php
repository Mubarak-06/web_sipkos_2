<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'kos_id',
        'nama_penyewa',
        'tanggal_checkin',
        'durasi',
        'jasa_pindahan',
        'nama_jasa_pindahan',
        'total_harga',
        'status'
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }
}
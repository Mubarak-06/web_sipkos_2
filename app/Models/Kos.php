<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class Kos extends Model
{
    protected $table = 'kos';

    protected $fillable = [
        'nama',
        'lokasi',
        'latitude',
        'longitude',
        'alamat',
        'harga',
        'tipe_kos',
        'ac',
        'wifi',
        'kamar_mandi_dalam',
        'deskripsi',
        'no_telepon',
        'foto',
        'foto_2',
        'foto_3'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_kos', 'id');
    }
}
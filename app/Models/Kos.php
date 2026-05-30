<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    // Mengarahkan model ini ke tabel 'kos' yang baru kita migrate tadi
    protected $table = 'kos';

    // PERBAIKAN: Menambahkan 'no_telepon' ke dalam array agar diizinkan masuk ke database
    protected $fillable = [
        'nama', 
        'lokasi', 
        'alamat',
        'harga', 
        'tipe_kos', 
        'ac', 
        'wifi', 
        'kamar_mandi_dalam', 
        'deskripsi', 
        'no_telepon'
    ];
}
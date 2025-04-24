<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';

    protected $fillable = [
        'universitas',
        'jumlah_anak',
        'tanggal_masuk',
        'tanggal_keluar',
        'deskripsi',
    ];
}

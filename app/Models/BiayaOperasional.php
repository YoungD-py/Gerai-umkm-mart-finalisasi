<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    use HasFactory;

    protected $table = 'biaya_operasional'; // Ini sudah benar, menunjuk ke nama tabel singular

    protected $fillable = [ // Ini adalah definisi $fillable yang asli dari file ZIP Anda
        'uraian',
        'nominal',
        'tanggal',
        'qty',
        'bukti_resi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Pastikan tidak ada baris 'use Illuminate\Database\Eloquent\SoftDeletes;' di sini.
    // Juga, pastikan tidak ada 'protected $dates = ['deleted_at'];' di sini.
    // Jika ada, hapuslah untuk memastikan penghapusan permanen.
}

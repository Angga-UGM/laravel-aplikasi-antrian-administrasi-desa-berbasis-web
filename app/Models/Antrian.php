<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = [
        'nomor_antrian', 'nama', 'nik', 'jenis_kelamin',
        'alamat', 'keperluan', 'status',
        'tanggal_antrian', 'waktu_daftar', 'waktu_panggil', 'waktu_selesai'
    ];

    protected $casts = [
        'tanggal_antrian' => 'date',
        'waktu_daftar' => 'datetime',
        'waktu_panggil' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];
}
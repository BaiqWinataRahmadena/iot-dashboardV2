<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan'; // Nama tabel di DB
    protected $primaryKey = 'id_pelanggan'; // Primary key Anda
    public $timestamps = false; // Jika tabel tidak punya created_at/updated_at otomatis

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama', 'alamat_rumah', 'no_ktp', 'status', 'telepon', 'pekerjaan', 'keterangan'
    ];
}
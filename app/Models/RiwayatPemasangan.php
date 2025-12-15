<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPemasangan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pemasangan';
    protected $primaryKey = 'id_riwayat';
    protected $fillable = ['id_pelanggan', 'tipe_meteran', 'diameter_meteran', 'tanggal_pasang', 'keterangan'];

    // Matikan timestamp jika tidak ingin error
    // atau pastikan tabel punya created_at/updated_at
}
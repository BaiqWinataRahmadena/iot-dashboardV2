<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $table = 'pelaporan_hasil_baca';
    protected $primaryKey = 'id_pelaporan';
    
    // Matikan timestamps default Laravel jika kolom Anda namanya beda atau tidak ada
    public $timestamps = false; 

    protected $fillable = [
        'id_pelanggan', 
        'device_id',
        'nama_petugas', 
        'tanggal_baca', 
        'kondisi_perangkat',
        'status_pengukuran', // Kita butuh kolom ini untuk logika "Menunggu"
    ];

    // Relasi ke pelanggan (agar bisa panggil $laporan->pelanggan->nama)
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
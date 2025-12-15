<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pelanggan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@pdam.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // 2. Buat Akun Petugas Lapangan
        User::create([
            'name' => 'Budi Petugas',
            'email' => 'budi@pdam.com',
            'password' => Hash::make('petugas123'),
            'email_verified_at' => now(),
        ]);

        // 3. Buat Data Pelanggan Dummy
        Pelanggan::create([
            'nama' => 'Budi Santoso (SQLite)',
            'alamat_rumah' => 'Jl. Merdeka No. 1, Mataram',
            'status' => 'aktif',
            'latitude' => -8.583333,
            'longitude' => 116.116669,
            'no_ktp' => '5201234567890001',
            'telepon' => '081234567890'
        ]);

        Pelanggan::create([
            'nama' => 'Siti Aminah',
            'alamat_rumah' => 'Jl. Udayana No. 5, Mataram',
            'status' => 'aktif',
            'latitude' => -8.590000,
            'longitude' => 116.120000,
            'no_ktp' => '5201234567890002',
            'telepon' => '081987654321'
        ]);
    }
}
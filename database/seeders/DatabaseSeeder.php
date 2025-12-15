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
        // 1. Buat Akun Admin (Cek berdasarkan email agar tidak duplikat)
        User::firstOrCreate(
            ['email' => 'admin@pdam.com'], // <- Kunci pencarian
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Buat Akun Petugas Lapangan
        User::firstOrCreate(
            ['email' => 'budi@pdam.com'], // <- Kunci pencarian
            [
                'name' => 'Budi Petugas',
                'password' => Hash::make('petugas123'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Buat Data Pelanggan Dummy (Cek berdasarkan No KTP)
        Pelanggan::firstOrCreate(
            ['no_ktp' => '5201234567890001'], // <- Kunci pencarian
            [
                'nama' => 'Budi Santoso (SQLite)',
                'alamat_rumah' => 'Jl. Merdeka No. 1, Mataram',
                'status' => 'aktif',
                'latitude' => -8.583333,
                'longitude' => 116.116669,
                'telepon' => '081234567890'
            ]
        );

        Pelanggan::firstOrCreate(
            ['no_ktp' => '5201234567890002'], // <- Kunci pencarian
            [
                'nama' => 'Siti Aminah',
                'alamat_rumah' => 'Jl. Udayana No. 5, Mataram',
                'status' => 'aktif',
                'latitude' => -8.590000,
                'longitude' => 116.120000,
                'telepon' => '081987654321'
            ]
        );
    }
}
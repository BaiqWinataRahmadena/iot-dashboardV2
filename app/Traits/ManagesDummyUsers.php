<?php

namespace App\Traits;

trait ManagesDummyUsers
{
    /**
     * Data Dummy untuk tabel 'users' (Karyawan)
     */
    private function getDummyUsers()
    {
        return collect([
            (object)[
                'id' => 1,
                'name' => 'Admin Utama',
                'email' => 'admin@pdam.com',
                'password' => 'admin123', // <-- PASSWORD DUMMY
                'email_verified_at' => '2025-01-01 10:00:00',
                'remember_token' => 'abc123xyz789',
                'created_at' => '2025-01-01 10:00:00',
                'updated_at' => '2025-01-01 10:00:00'
            ],
            (object)[
                'id' => 2,
                'name' => 'Andi (Petugas Lapangan)',
                'email' => 'andi.petugas@pdam.com',
                'password' => 'andi123', // <-- PASSWORD DUMMY
                'email_verified_at' => '2025-01-02 11:00:00',
                'remember_token' => 'def456uvw123',
                'created_at' => '2025-01-02 11:00:00',
                'updated_at' => '2025-01-02 11:00:00'
            ],
            (object)[
                'id' => 3,
                'name' => 'Rian (Petugas Lapangan)',
                'email' => 'rian.petugas@pdam.com',
                'password' => 'rian123', // <-- PASSWORD DUMMY
                'email_verified_at' => '2025-01-02 11:05:00',
                'remember_token' => 'ghi789rst456',
                'created_at' => '2025-01-02 11:05:00',
                'updated_at' => '2025-01-02 11:05:00'
            ],
            (object)[
                'id' => 4,
                'name' => 'Siti (Admin Kantor)',
                'email' => 'siti.admin@pdam.com',
                'password' => 'siti123', // <-- PASSWORD DUMMY
                'email_verified_at' => null,
                'remember_token' => null,
                'created_at' => '2025-03-10 14:00:00',
                'updated_at' => '2025-03-10 14:00:00'
            ],
        ]);
    }
}
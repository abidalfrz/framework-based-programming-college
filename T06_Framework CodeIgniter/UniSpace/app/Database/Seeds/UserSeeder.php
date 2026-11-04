<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'nama'          => 'Administrator Sarpras',
                'username'      => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Staff Pengelola Fasilitas',
                'username'      => 'staff',
                'password_hash' => password_hash('staff123', PASSWORD_DEFAULT),
                'role'          => 'staff',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);
    }
}


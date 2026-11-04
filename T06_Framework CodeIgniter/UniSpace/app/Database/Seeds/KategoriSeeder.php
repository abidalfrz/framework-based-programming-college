<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        helper('url');

        $now = date('Y-m-d H:i:s');
        $data = [
            ['LAB', 'Laboratorium & Komputasi', 'bi-laptop', 'Laboratorium komputer, riset kecerdasan buatan, IoT, dan workstation berkinerja tinggi.'],
            ['KUL', 'Ruang Kuliah & Teater', 'bi-easel', 'Ruang kuliah bersama, ruang kelas interaktif, dan amfiteater kuliah umum.'],
            ['SEM', 'Ruang Seminar & Rapat', 'bi-people', 'Ruang rapat senat, ruang sidang skripsi, dan discussion room co-working.'],
            ['ALT', 'Perangkat & Multimedia', 'bi-projector', 'Peralatan presentasi, proyektor laser 4K, smart board, dan perlengkapan audio visual.'],
            ['OLA', 'Sarana Olahraga & Seni', 'bi-trophy', 'Gelanggang serbaguna mahasiswa, lapangan futsal, lapangan basket, dan studio musik.'],
        ];

        $rows = array_map(static fn (array $k): array => [
            'kode'       => $k[0],
            'nama'       => $k[1],
            'slug'       => url_title($k[1], '-', true),
            'icon'       => $k[2],
            'deskripsi'  => $k[3],
            'created_at' => $now,
            'updated_at' => $now,
        ], $data);

        $this->db->table('kategori_fasilitas')->insertBatch($rows);
    }
}


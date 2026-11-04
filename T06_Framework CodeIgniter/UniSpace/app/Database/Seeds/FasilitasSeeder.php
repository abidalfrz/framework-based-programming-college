<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        helper('url');

        $now = date('Y-m-d H:i:s');

        // Mengambil kategori id berdasarkan kode
        $kategori = [];
        $rows = $this->db->table('kategori_fasilitas')->get()->getResultArray();
        foreach ($rows as $r) {
            $kategori[$r['kode']] = (int) $r['id'];
        }

        $fasilitas = [
            [
                'kode'             => 'LAB-AI-01',
                'nama'             => 'Lab AI & High Performance Computing',
                'kategori_kode'    => 'LAB',
                'lokasi'           => 'Gedung Riset Informatika Lt. 3',
                'kapasitas'        => 35,
                'spesifikasi'      => '35 Unit Workstation Intel Core i9, 64GB RAM, Dual NVIDIA RTX 4090, Gigabit LAN, Gigabit Switch, AC 3 PK, Smart Interactive Display 85 Inci.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Budi Santoso, S.Kom., M.T.',
                'foto'             => null,
            ],
            [
                'kode'             => 'LAB-NET-02',
                'nama'             => 'Lab Cyber Security & Cloud Network',
                'kategori_kode'    => 'LAB',
                'lokasi'           => 'Gedung Riset Informatika Lt. 2',
                'kapasitas'        => 40,
                'spesifikasi'      => '40 PC Core i7, 32GB RAM, Cisco Routers & Switches Rack, Mikrotik CCR, Firewall FortiGate, Server Virtualisasi Proxmox.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Rahmat Hidayat, M.Kom.',
                'foto'             => null,
            ],
            [
                'kode'             => 'KUL-AUD-01',
                'nama'             => 'Auditorium Graha Widya Utama',
                'kategori_kode'    => 'KUL',
                'lokasi'           => 'Gedung Rektorat Sayap Barat',
                'kapasitas'        => 600,
                'spesifikasi'      => 'Videowall LED P2.5 12x4 Meter, Sound System Line Array Yamaha 10.000W, Lighting Stage DMX, 4 Wireless Mic Shure, Kursi Theater Berundak, Central AC.',
                'status'           => 'reservasi',
                'penanggung_jawab' => 'Ir. Hendra Kusuma',
                'foto'             => null,
            ],
            [
                'kode'             => 'KUL-CLS-204',
                'nama'             => 'Smart Classroom Interaktif 204',
                'kategori_kode'    => 'KUL',
                'lokasi'           => 'Tower Bersama Kuliah Lt. 2',
                'kapasitas'        => 60,
                'spesifikasi'      => 'Dual Interactive Board 75 Inci, Hybrid Camera PTZ Auto-Tracking, Ceiling Microphone Array, AC 2 PK, Kursi Meja Ergonomis Fleksibel.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Dewi Lestari, S.Pd.',
                'foto'             => null,
            ],
            [
                'kode'             => 'SEM-EXE-01',
                'nama'             => 'Executive Senate Meeting Room',
                'kategori_kode'    => 'SEM',
                'lokasi'           => 'Gedung Rektorat Lt. 3',
                'kapasitas'        => 25,
                'spesifikasi'      => 'Oval Conference Table, Microphone Delegate Bosch per Kursi, Smart Screen 85 Inci, Video Conference Poly Studio, Pantry VIP, Kedap Suara.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Siti Nurhaliza, S.Sos.',
                'foto'             => null,
            ],
            [
                'kode'             => 'SEM-COW-03',
                'nama'             => 'Creative Co-Working & Discussion Hub',
                'kategori_kode'    => 'SEM',
                'lokasi'           => 'Perpustakaan Pusat Lt. 1',
                'kapasitas'        => 20,
                'spesifikasi'      => '6 Sofa Booth Diskusi, Glass Whiteboard, TV Display 55 Inci Wireless Cast, Stopkontak Universal, Free High-Speed WiFi 500 Mbps.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Fajar Nugroho, S.I.Pust.',
                'foto'             => null,
            ],
            [
                'kode'             => 'ALT-PRJ-01',
                'nama'             => 'Mobile 4K Laser Projector Unit 1',
                'kategori_kode'    => 'ALT',
                'lokasi'           => 'Unit Layanan Sarpras Lt. 1',
                'kapasitas'        => 1,
                'spesifikasi'      => 'Epson Pro Laser 8000 Lumens, Resolusi 4K UHD Enhancement, Tripod Screen 200 Inci, Kabel HDMI Fiber Optic 30m, Hard Flight Case.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Agus Pratama (Teknisi Sarpras)',
                'foto'             => null,
            ],
            [
                'kode'             => 'ALT-SND-02',
                'nama'             => 'Portable PA Sound System 1000W',
                'kategori_kode'    => 'ALT',
                'lokasi'           => 'Unit Layanan Sarpras Lt. 1',
                'kapasitas'        => 1,
                'spesifikasi'      => 'Yamaha StagePas 1K Portable Column PA System, Built-in 5-Channel Digital Mixer, Bluetooth Audio, 2 Wireless Mic Sennheiser, Rolling Case.',
                'status'           => 'pemeliharaan',
                'penanggung_jawab' => 'Agus Pratama (Teknisi Sarpras)',
                'foto'             => null,
            ],
            [
                'kode'             => 'OLA-FUT-01',
                'nama'             => 'Lapangan Futsal & Badminton Indoor',
                'kategori_kode'    => 'OLA',
                'lokasi'           => 'Sport Center Kampus Timur',
                'kapasitas'        => 150,
                'spesifikasi'      => 'Lantai Vinyl Standar BWF/FIFA, Lampu Sorot LED 1000 Lux Anti-Silau, Tribun Penonton, Kamar Mandi & Ruang Ganti Pemain, Papan Skor Digital.',
                'status'           => 'tersedia',
                'penanggung_jawab' => 'Wahyu Wicaksono',
                'foto'             => null,
            ],
        ];

        $insertData = [];
        foreach ($fasilitas as $item) {
            $katId = $kategori[$item['kategori_kode']] ?? null;
            if ($katId === null) {
                continue;
            }

            $insertData[] = [
                'kode'             => $item['kode'],
                'nama'             => $item['nama'],
                'slug'             => url_title($item['nama'], '-', true),
                'kategori_id'      => $katId,
                'lokasi'           => $item['lokasi'],
                'kapasitas'        => $item['kapasitas'],
                'spesifikasi'      => $item['spesifikasi'],
                'status'           => $item['status'],
                'penanggung_jawab' => $item['penanggung_jawab'],
                'foto'             => null,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        $this->db->table('fasilitas')->insertBatch($insertData);
    }
}


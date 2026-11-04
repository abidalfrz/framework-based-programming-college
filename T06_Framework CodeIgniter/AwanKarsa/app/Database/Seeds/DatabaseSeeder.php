<?php

namespace App\Database\Seeds;

use App\Models\KategoriModel;
use App\Models\OrderItemModel;
use App\Models\ProdukModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

/**
 * Data contoh AwanKarsa: 1 admin, 2 pembeli, 4 kategori, 14 produk,
 * dan beberapa pesanan dengan status berbeda untuk dashboard.
 */
class DatabaseSeeder extends Seeder
{
    /** [nama, ikon bootstrap-icons] */
    private const KATEGORI = [
        ['Cloud VPS', 'cloud'],
        ['Dedicated GPU Server Lab', 'gpu-card'],
        ['Web Hosting Mahasiswa', 'hdd-network'],
        ['Cloud Storage', 'cloud-arrow-up'],
    ];

    /** [index kategori, nama, harga per bulan, stok, deskripsi] */
    private const PRODUK = [
        [0, 'Ubuntu Server 2 vCPU 4 GB RAM', 99000, 20, 'VPS Ubuntu dengan 2 vCPU, 4 GB RAM dan SSD 40 GB. Harga untuk 1 bulan.'],
        [0, 'VPS Developer 1 vCPU 2 GB RAM', 59000, 30, 'VPS Linux untuk latihan dan aplikasi kecil, SSD 25 GB. Harga untuk 1 bulan.'],
        [0, 'VPS Pro 4 vCPU 8 GB RAM', 199000, 10, 'VPS untuk aplikasi dan basis data, SSD 80 GB. Harga untuk 1 bulan.'],
        [0, 'VPS Riset 8 vCPU 16 GB RAM', 399000, 5, 'VPS untuk eksperimen komputasi dan layanan kampus, SSD 160 GB. Harga untuk 1 bulan.'],
        [1, 'GPU Server PyTorch', 899000, 4, 'Server lab GPU untuk eksperimen PyTorch dan pembelajaran mesin. Harga untuk 1 bulan.'],
        [1, 'GPU Server TensorFlow', 899000, 4, 'Server lab GPU untuk eksperimen TensorFlow. Harga untuk 1 bulan.'],
        [1, 'GPU Server Computer Vision', 1199000, 0, 'Server lab untuk eksperimen pengolahan citra. Harga untuk 1 bulan.'],
        [2, 'Hosting Portofolio Mahasiswa', 25000, 50, 'Hosting untuk portofolio dan proyek web mahasiswa. Harga untuk 1 bulan.'],
        [2, 'Hosting Proyek Web', 49000, 30, 'Hosting untuk aplikasi web dan basis data proyek. Harga untuk 1 bulan.'],
        [2, 'Hosting Tim Kampus', 89000, 15, 'Hosting untuk proyek kolaborasi tim. Harga untuk 1 bulan.'],
        [2, 'Hosting Praktikum', 35000, 20, 'Hosting untuk latihan deployment aplikasi. Harga untuk 1 bulan.'],
        [3, 'Cloud Storage 100 GB', 29000, 40, 'Penyimpanan cloud 100 GB untuk berkas proyek. Harga untuk 1 bulan.'],
        [3, 'Cloud Storage 500 GB', 79000, 20, 'Penyimpanan cloud 500 GB untuk data penelitian. Harga untuk 1 bulan.'],
        [3, 'Cloud Storage 1 TB', 139000, 10, 'Penyimpanan cloud 1 TB untuk kebutuhan tim. Harga untuk 1 bulan.'],
    ];

    public function run(): void
    {
        $db = \App\Libraries\DemoSeedGuard::requireEmpty();
        $db->transException(true)->transBegin();
        try {
            $this->seedDemo();
            if (! $db->transStatus()) {
                throw new \RuntimeException('Demo seed gagal; transaksi dibatalkan.');
            }
            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    private function seedDemo(): void
    {
        $users = new UserModel();
        $users->insert([
            'nama' => 'Admin AwanKarsa', 'email' => 'admin@awankarsa.test', 'role' => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
        ]);
        $budi = $users->insert([
            'nama' => 'Budi Setiawan', 'email' => 'budi@mail.test', 'role' => 'pembeli',
            'password_hash' => password_hash('pembeli123', PASSWORD_DEFAULT),
            'telepon' => '081234567890', 'alamat' => "Asrama Mahasiswa Blok C No. 12\nJl. Teknik Kimia, Sukolilo, Surabaya 60111",
        ]);
        $sari = $users->insert([
            'nama' => 'Sari Wulandari', 'email' => 'sari@mail.test', 'role' => 'pembeli',
            'password_hash' => password_hash('pembeli123', PASSWORD_DEFAULT),
            'telepon' => '085711112222', 'alamat' => "Jl. Keputih Tegal No. 8\nSukolilo, Surabaya 60111",
        ]);

        $kategoriModel = new KategoriModel();
        $kategori      = [];
        foreach (self::KATEGORI as [$nama, $ikon]) {
            $kategori[] = $kategoriModel->insert(['nama' => $nama, 'ikon' => $ikon]);
        }

        $produkModel = new ProdukModel();
        $produk      = [];
        foreach (self::PRODUK as $i => [$k, $nama, $harga, $stok, $deskripsi]) {
            $id = $produkModel->skipValidation(true)->insert([
                'kategori_id' => $kategori[$k], 'nama' => $nama, 'deskripsi' => $deskripsi,
                'harga' => $harga, 'stok' => $stok, 'aktif' => 1,
            ]);
            // Model selalu mengisi created_at = sekarang; tanggal dibuat berbeda agar urutan "Terbaru" bermakna.
            $this->db->table('produk')->where('id', $id)
                ->update(['created_at' => date('Y-m-d H:i:s', strtotime('-' . (count(self::PRODUK) - $i) . ' days'))]);
            $produk[] = $id;
        }

        // [user, status, hari lalu, [[index produk, qty]], resi]
        $pesanan = [
            [$budi, 'selesai', 6, [[4, 1], [1, 2]], 'AKTIF001'],
            [$sari, 'selesai', 5, [[8, 1]], 'AKTIF002'],
            [$budi, 'dikirim', 3, [[11, 1], [0, 3]], 'AKTIF003'],
            [$sari, 'diproses', 1, [[9, 2], [2, 1]], null],
            [$budi, 'dibatalkan', 2, [[3, 1]], null],
            [$sari, 'menunggu_pembayaran', 0, [[7, 2]], null],
        ];
        $items  = new OrderItemModel();
        foreach ($pesanan as $n => [$userId, $status, $hari, $baris, $resi]) {
            $user     = $users->find($userId);
            $subtotal = 0;
            foreach ($baris as [$p, $qty]) {
                $subtotal += self::PRODUK[$p][2] * $qty;
            }
            $ongkir  = 0;
            $waktu   = date('Y-m-d H:i:s', strtotime("-{$hari} days 10:15"));
            // Builder dipakai langsung agar created_at bisa diisi tanggal lampau.
            $this->db->table('orders')->insert([
                'kode' => 'AK' . date('ymd', strtotime("-{$hari} days")) . '-' . sprintf('%04X', 0xA100 + $n),
                'user_id' => $userId, 'status' => $status, 'subtotal' => $subtotal, 'ongkir' => $ongkir,
                'total' => $subtotal + $ongkir, 'nama_penerima' => $user['nama'], 'telepon' => $user['telepon'],
                'alamat' => $user['alamat'], 'resi' => $resi, 'created_at' => $waktu, 'updated_at' => $waktu,
            ]);
            $orderId = $this->db->insertID();
            foreach ($baris as [$p, $qty]) {
                $items->insert([
                    'order_id' => $orderId, 'produk_id' => $produk[$p], 'nama_produk' => self::PRODUK[$p][1],
                    'harga' => self::PRODUK[$p][2], 'qty' => $qty, 'subtotal' => self::PRODUK[$p][2] * $qty,
                ]);
            }
        }
    }
}

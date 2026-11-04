<?php

namespace App\Database\Seeds;

use App\Libraries\Penilaian;
use App\Models\DosenModel;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

/**
 * Data contoh: 1 admin, 4 dosen, 9 mahasiswa, 16 mata kuliah,
 * riwayat nilai angkatan 2025 (2 semester) dan KRS semester berjalan.
 * Password awal dosen = NIDN, mahasiswa = NIM.
 */
class DatabaseSeeder extends Seeder
{
    private const DOSEN = [
        ['0011018501', 'Dr. Budi Santoso, S.Kom., M.Kom.', 'budi.santoso@kampus.ac.id'],
        ['0012028602', 'Siti Rahmawati, S.T., M.T.', 'siti.rahmawati@kampus.ac.id'],
        ['0013038703', 'Ir. Agus Wijaya, M.Sc.', 'agus.wijaya@kampus.ac.id'],
        ['0014048804', 'Dewi Lestari, S.Kom., M.Cs.', 'dewi.lestari@kampus.ac.id'],
    ];

    /** [kode, nama, sks, semester, index dosen, kuota] */
    private const MATAKULIAH = [
        ['IF1101', 'Dasar Pemrograman', 4, 1, 0, 40],
        ['IF1102', 'Matematika Diskrit', 3, 1, 2, 40],
        ['IF1103', 'Pengantar Teknologi Informasi', 2, 1, 1, 40],
        ['UM1101', 'Pancasila', 2, 1, 3, 60],
        ['UM1102', 'Bahasa Indonesia', 2, 1, 3, 60],
        ['IF1201', 'Struktur Data', 4, 2, 0, 40],
        ['IF1202', 'Sistem Digital', 3, 2, 2, 40],
        ['IF1203', 'Aljabar Linear', 3, 2, 2, 40],
        ['IF1204', 'Organisasi Komputer', 3, 2, 1, 40],
        ['UM1201', 'Bahasa Inggris', 2, 2, 3, 60],
        ['IF2301', 'Pemrograman Berbasis Kerangka Kerja', 3, 3, 0, 40],
        ['IF2302', 'Basis Data', 4, 3, 1, 40],
        ['IF2303', 'Pemrograman Berorientasi Objek', 3, 3, 0, 40],
        ['IF2304', 'Jaringan Komputer', 3, 3, 2, 40],
        ['IF2305', 'Probabilitas dan Statistik', 3, 3, 3, 40],
        ['IF2306', 'Sistem Operasi', 4, 3, 1, 3],
    ];

    /** [nim, nama, angkatan, index dosen wali, rata-rata nilai] */
    private const MAHASISWA = [
        ['5025251001', 'Andi Pratama', 2025, 0, 88],
        ['5025251002', 'Bunga Maharani', 2025, 0, 80],
        ['5025251003', 'Cahyo Nugroho', 2025, 1, 74],
        ['5025251004', 'Dinda Ayu Lestari', 2025, 1, 70],
        ['5025251005', 'Eko Saputra', 2025, 2, 82],
        ['5025251006', 'Fajar Ramadhan', 2025, 2, 52],
        ['5025261001', 'Gita Permata', 2026, 3, 0],
        ['5025261002', 'Hendra Wijaya', 2026, 3, 0],
        ['5025261003', 'Intan Puspita', 2026, 0, 0],
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
        mt_srand(2026); // nilai acak tetapi sama setiap kali seeding

        $users = new UserModel();
        $users->insert([
            'username'      => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'nama'          => 'Administrator Akademik',
            'role'          => 'admin',
        ]);

        $dosenId = $this->seedDosen($users);
        $mkId    = $this->seedMatakuliah($dosenId);
        $mhsId   = $this->seedMahasiswa($users, $dosenId);
        $this->seedRiwayatNilai($mhsId, $mkId);
        $this->seedKrsBerjalan($mhsId, $mkId);
    }

    /** @return list<int> */
    private function seedDosen(UserModel $users): array
    {
        $model = new DosenModel();
        $ids   = [];

        foreach (self::DOSEN as [$nidn, $nama, $email]) {
            $userId = $users->insert([
                'username'      => $nidn,
                'password_hash' => password_hash($nidn, PASSWORD_DEFAULT),
                'nama'          => $nama,
                'role'          => 'dosen',
            ]);
            $ids[] = $model->insert(['user_id' => $userId, 'nidn' => $nidn, 'email' => $email]);
        }

        return $ids;
    }

    /** @return array<string, int> kode => id */
    private function seedMatakuliah(array $dosenId): array
    {
        $model = new MatakuliahModel();
        $ids   = [];

        foreach (self::MATAKULIAH as [$kode, $nama, $sks, $semester, $dosen, $kuota]) {
            $ids[$kode] = $model->skipValidation(true)->insert([
                'kode' => $kode, 'nama' => $nama, 'sks' => $sks, 'semester' => $semester,
                'dosen_id' => $dosenId[$dosen], 'kuota' => $kuota,
            ]);
        }

        return $ids;
    }

    /** @return array<string, array{id: int, rata: int, angkatan: int}> nim => data */
    private function seedMahasiswa(UserModel $users, array $dosenId): array
    {
        $model = new MahasiswaModel();
        $data  = [];

        foreach (self::MAHASISWA as [$nim, $nama, $angkatan, $wali, $rata]) {
            $userId = $users->insert([
                'username'      => $nim,
                'password_hash' => password_hash($nim, PASSWORD_DEFAULT),
                'nama'          => $nama,
                'role'          => 'mahasiswa',
            ]);
            $id = $model->insert([
                'user_id'       => $userId,
                'nim'           => $nim,
                'email'         => strtolower(strtok($nama, ' ')) . '.' . substr($nim, -4) . '@student.kampus.ac.id',
                'angkatan'      => $angkatan,
                'dosen_wali_id' => $dosenId[$wali],
            ]);
            $data[$nim] = ['id' => $id, 'rata' => $rata, 'angkatan' => $angkatan];
        }

        return $data;
    }

    /** Angkatan 2025 sudah menempuh semester 1 (2025/2026 ganjil) dan 2 (genap). */
    private function seedRiwayatNilai(array $mahasiswa, array $mkId): void
    {
        $krs       = new KrsModel();
        $penilaian = new Penilaian();

        // Catatan: kunci array NIM berubah menjadi int oleh PHP, maka dibandingkan sebagai string.
        foreach ($mahasiswa as $nim => $m) {
            if ($m['angkatan'] !== 2025) {
                continue;
            }
            foreach (self::MATAKULIAH as [$kode, , , $semester]) {
                if ($semester > 2) {
                    continue;
                }
                $nilai = max(0, min(100, $m['rata'] + mt_rand(-8, 8)));
                if ((string) $nim === '5025251006' && $kode === 'IF1102') {
                    $nilai = 45; // nilai D -> boleh mengulang di semester ganjil ini
                }
                $krs->insert([
                    'mahasiswa_id'  => $m['id'],
                    'matakuliah_id' => $mkId[$kode],
                    'tahun_ajaran'  => '2025/2026',
                    'semester'      => $semester === 1 ? 'ganjil' : 'genap',
                    'nilai_angka'   => $nilai,
                    'nilai_huruf'   => $penilaian->huruf($nilai),
                ]);
            }
        }
    }

    /** KRS 2026/2027 ganjil yang sudah diisi sebagian mahasiswa (belum dinilai). */
    private function seedKrsBerjalan(array $mahasiswa, array $mkId): void
    {
        $rencana = [
            '5025251002' => ['IF2301', 'IF2302', 'IF2303', 'IF2304', 'IF2306'],
            '5025251003' => ['IF2301', 'IF2302', 'IF2303', 'IF2304', 'IF2306'],
            '5025251004' => ['IF2301', 'IF2302', 'IF2303', 'IF2306'],
            '5025251005' => ['IF2301', 'IF2302', 'IF2304', 'IF2305'],
            '5025261001' => ['IF1101', 'IF1102', 'IF1103', 'UM1101', 'UM1102'],
        ];

        $krs = new KrsModel();
        foreach ($rencana as $nim => $kodeList) {
            foreach ($kodeList as $kode) {
                $krs->insert([
                    'mahasiswa_id'  => $mahasiswa[$nim]['id'],
                    'matakuliah_id' => $mkId[$kode],
                    'tahun_ajaran'  => '2026/2027',
                    'semester'      => 'ganjil',
                ]);
            }
        }
    }
}

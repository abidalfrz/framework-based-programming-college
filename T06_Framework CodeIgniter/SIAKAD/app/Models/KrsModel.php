<?php

namespace App\Models;

use CodeIgniter\Model;

class KrsModel extends Model
{
    protected $table         = 'krs';
    protected $returnType    = 'array';
    protected $allowedFields = ['mahasiswa_id', 'matakuliah_id', 'tahun_ajaran', 'semester', 'nilai_angka', 'nilai_huruf'];
    protected $useTimestamps = true;

    private function denganMatakuliah(): static
    {
        return $this->select('krs.*, mk.kode, mk.nama AS nama_mk, mk.sks, mk.semester AS semester_mk')
            ->join('matakuliah mk', 'mk.id = krs.matakuliah_id');
    }

    /** Seluruh riwayat KRS mahasiswa, urut periode lalu kode MK. */
    public function riwayat(int $mahasiswaId): array
    {
        return $this->denganMatakuliah()
            ->where('krs.mahasiswa_id', $mahasiswaId)
            ->orderBy('krs.tahun_ajaran')
            ->orderBy('krs.semester') // ENUM diurutkan sesuai definisi: ganjil, genap
            ->orderBy('mk.kode')
            ->findAll();
    }

    public function riwayatMatakuliah(int $mahasiswaId, int $matakuliahId): array
    {
        return $this->where(['mahasiswa_id' => $mahasiswaId, 'matakuliah_id' => $matakuliahId])->findAll();
    }

    public function riwayatById(int $mahasiswaId, int $krsId): ?array
    {
        return $this->denganMatakuliah()
            ->where(['krs.id' => $krsId, 'krs.mahasiswa_id' => $mahasiswaId])
            ->first();
    }

    /** KRS satu periode lengkap dengan nama dosen pengampu. */
    public function periode(int $mahasiswaId, string $tahunAjaran, string $semester): array
    {
        return $this->denganMatakuliah()
            ->select('ud.nama AS nama_dosen')
            ->join('dosen d', 'd.id = mk.dosen_id')
            ->join('users ud', 'ud.id = d.user_id')
            ->where([
                'krs.mahasiswa_id' => $mahasiswaId,
                'krs.tahun_ajaran' => $tahunAjaran,
                'krs.semester'     => $semester,
            ])
            ->orderBy('mk.kode')
            ->findAll();
    }

    /** @return list<array{tahun_ajaran: string, semester: string}> */
    public function daftarPeriode(int $mahasiswaId): array
    {
        return $this->select('tahun_ajaran, semester')
            ->distinct()
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('tahun_ajaran')
            ->orderBy('semester')
            ->findAll();
    }

    public function sksDiambil(int $mahasiswaId, string $tahunAjaran, string $semester): int
    {
        $baris = $this->selectSum('mk.sks', 'total')
            ->join('matakuliah mk', 'mk.id = krs.matakuliah_id')
            ->where([
                'krs.mahasiswa_id' => $mahasiswaId,
                'krs.tahun_ajaran' => $tahunAjaran,
                'krs.semester'     => $semester,
            ])
            ->first();

        return (int) ($baris['total'] ?? 0);
    }

    public function jumlahPeserta(int $matakuliahId, string $tahunAjaran, string $semester): int
    {
        return $this->where([
            'matakuliah_id' => $matakuliahId,
            'tahun_ajaran'  => $tahunAjaran,
            'semester'      => $semester,
        ])->countAllResults();
    }

    /** Peserta kelas (untuk input nilai dosen). */
    public function peserta(int $matakuliahId, string $tahunAjaran, string $semester): array
    {
        return $this->select('krs.*, m.nim, u.nama')
            ->join('mahasiswa m', 'm.id = krs.mahasiswa_id')
            ->join('users u', 'u.id = m.user_id')
            ->where([
                'krs.matakuliah_id' => $matakuliahId,
                'krs.tahun_ajaran'  => $tahunAjaran,
                'krs.semester'      => $semester,
            ])
            ->orderBy('m.nim')
            ->findAll();
    }
}

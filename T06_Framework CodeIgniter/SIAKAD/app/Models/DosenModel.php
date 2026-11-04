<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table         = 'dosen';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'nidn', 'email'];
    protected $useTimestamps = true;

    /** Dosen beserta nama/username dari tabel users. */
    public function lengkap(): static
    {
        return $this->select('dosen.*, u.nama, u.username')
            ->join('users u', 'u.id = dosen.user_id');
    }

    public function byUserId(int $userId): ?array
    {
        return $this->lengkap()->where('dosen.user_id', $userId)->first();
    }

    public function findLengkap(int $id): ?array
    {
        return $this->lengkap()->where('dosen.id', $id)->first();
    }

    /** Daftar dosen untuk tabel admin, dengan jumlah mata kuliah & mahasiswa wali. */
    public function daftar(): array
    {
        return $this->lengkap()
            ->select('(SELECT COUNT(*) FROM matakuliah mk WHERE mk.dosen_id = dosen.id) AS jumlah_mk', false)
            ->select('(SELECT COUNT(*) FROM mahasiswa m WHERE m.dosen_wali_id = dosen.id) AS jumlah_wali', false)
            ->orderBy('u.nama')
            ->findAll();
    }

    /** @return array<int, string> id => nama, untuk dropdown */
    public function pilihan(): array
    {
        $hasil = [];
        foreach ($this->lengkap()->orderBy('u.nama')->findAll() as $d) {
            $hasil[(int) $d['id']] = $d['nama'];
        }

        return $hasil;
    }
}

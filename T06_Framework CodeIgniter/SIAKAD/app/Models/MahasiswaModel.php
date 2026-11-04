<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table         = 'mahasiswa';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'nim', 'email', 'angkatan', 'dosen_wali_id'];
    protected $useTimestamps = true;

    /** Mahasiswa beserta nama (users) dan nama dosen wali. */
    public function lengkap(): static
    {
        return $this->select('mahasiswa.*, u.nama, u.username, w.nidn AS nidn_wali, uw.nama AS nama_wali')
            ->join('users u', 'u.id = mahasiswa.user_id')
            ->join('dosen w', 'w.id = mahasiswa.dosen_wali_id')
            ->join('users uw', 'uw.id = w.user_id');
    }

    public function findLengkap(int $id): ?array
    {
        return $this->lengkap()->where('mahasiswa.id', $id)->first();
    }

    public function byUserId(int $userId): ?array
    {
        return $this->lengkap()->where('mahasiswa.user_id', $userId)->first();
    }

    /** Pencarian NIM/nama dengan pagination untuk halaman admin. */
    public function cari(?string $keyword, int $perPage = 10): array
    {
        $this->lengkap();

        if ($keyword !== null && $keyword !== '') {
            $this->groupStart()
                ->like('mahasiswa.nim', $keyword)
                ->orLike('u.nama', $keyword)
                ->groupEnd();
        }

        return $this->orderBy('mahasiswa.nim')->paginate($perPage);
    }

    /** Mahasiswa perwalian seorang dosen. */
    public function perwalian(int $dosenId): array
    {
        return $this->lengkap()
            ->where('mahasiswa.dosen_wali_id', $dosenId)
            ->orderBy('mahasiswa.nim')
            ->findAll();
    }
}

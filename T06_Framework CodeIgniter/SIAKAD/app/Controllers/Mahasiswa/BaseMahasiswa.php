<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Libraries\Penilaian;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Akademik;

/**
 * Dasar semua halaman mahasiswa: data diambil dari mahasiswa yang sedang login saja.
 */
abstract class BaseMahasiswa extends BaseController
{
    protected Akademik $akademik;
    protected Penilaian $penilaian;
    protected KrsModel $krs;

    public function __construct()
    {
        $this->akademik  = config(Akademik::class);
        $this->penilaian = new Penilaian($this->akademik);
        $this->krs       = new KrsModel();
    }

    protected function mahasiswaId(): int
    {
        return (int) session('profil_id');
    }

    protected function profil(): array
    {
        return (new MahasiswaModel())->findLengkap($this->mahasiswaId())
            ?? throw PageNotFoundException::forPageNotFound('Data mahasiswa tidak ditemukan.');
    }
}

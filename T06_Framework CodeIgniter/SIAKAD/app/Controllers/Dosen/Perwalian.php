<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Libraries\KrsService;
use App\Libraries\Penilaian;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Akademik;

/**
 * Daftar mahasiswa perwalian beserta IPK & KRS semester berjalan.
 */
class Perwalian extends BaseController
{
    public function index(): string
    {
        $akademik  = config(Akademik::class);
        $penilaian = new Penilaian($akademik);
        $krs       = new KrsModel();

        $daftar = [];
        foreach ((new MahasiswaModel())->perwalian((int) session('profil_id')) as $m) {
            $riwayat  = $krs->riwayat((int) $m['id']);
            $daftar[] = $m + [
                'ipk'       => $penilaian->ipk($riwayat),
                'sks_lulus' => $penilaian->totalSks($penilaian->terbaikPerMatakuliah($riwayat)),
                'sks_ini'   => $krs->sksDiambil((int) $m['id'], $akademik->tahunAjaran, $akademik->semester),
            ];
        }

        return view('dosen/perwalian', ['title' => 'Mahasiswa Perwalian', 'mahasiswa' => $daftar, 'akademik' => $akademik]);
    }

    public function show(int $id): string
    {
        $mhs = (new MahasiswaModel())->findLengkap($id);
        if ($mhs === null || (int) $mhs['dosen_wali_id'] !== (int) session('profil_id')) {
            throw PageNotFoundException::forPageNotFound('Mahasiswa bukan perwalian Anda.');
        }

        $akademik = config(Akademik::class);
        $krs      = new KrsModel();
        $service  = new KrsService($akademik);

        return view('dosen/perwalian_detail', [
            'title'      => 'Perwalian ' . $mhs['nama'],
            'mhs'        => $mhs,
            'akademik'   => $akademik,
            'ringkasan'  => (new Penilaian($akademik))->perPeriode($krs->riwayat($id)),
            'krsIni'     => $krs->periode($id, $akademik->tahunAjaran, $akademik->semester),
            'batasSks'   => $service->batasSks($id),
        ]);
    }
}

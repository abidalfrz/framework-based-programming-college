<?php

namespace App\Controllers\Mahasiswa;

use App\Libraries\KrsService;

class Dashboard extends BaseMahasiswa
{
    public function index(): string
    {
        $riwayat = $this->krs->riwayat($this->mahasiswaId());
        $service = new KrsService($this->akademik);

        return view('mahasiswa/dashboard', [
            'title'       => 'Dashboard Mahasiswa',
            'mhs'         => $this->profil(),
            'akademik'    => $this->akademik,
            'ipk'         => $this->penilaian->ipk($riwayat),
            'sksLulus'    => $this->penilaian->totalSks($this->penilaian->terbaikPerMatakuliah($riwayat)),
            'perPeriode'  => $this->penilaian->perPeriode($riwayat),
            'sksIni'      => $this->krs->sksDiambil($this->mahasiswaId(), $this->akademik->tahunAjaran, $this->akademik->semester),
            'batasSks'    => $service->batasSks($this->mahasiswaId()),
            'krsTerbuka'  => $service->periodeTerbuka(),
        ]);
    }
}

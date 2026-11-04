<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahModel;
use Config\Akademik;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $akademik = config(Akademik::class);
        $krs      = new KrsModel();

        $distribusi = [];
        foreach ($akademik->skala as $s) {
            $distribusi[$s['huruf']] = 0;
        }
        $rows = $krs->select('nilai_huruf, COUNT(*) AS jumlah')
            ->where('nilai_huruf IS NOT NULL')
            ->groupBy('nilai_huruf')
            ->findAll();
        foreach ($rows as $r) {
            $distribusi[$r['nilai_huruf']] = (int) $r['jumlah'];
        }

        return view('admin/dashboard', [
            'title'          => 'Dashboard Admin',
            'akademik'       => $akademik,
            'jumlahMhs'      => (new MahasiswaModel())->countAllResults(),
            'jumlahDosen'    => (new DosenModel())->countAllResults(),
            'jumlahMk'       => (new MatakuliahModel())->countAllResults(),
            'jumlahKrs'      => $krs->where(['tahun_ajaran' => $akademik->tahunAjaran, 'semester' => $akademik->semester])->countAllResults(),
            'ditawarkan'     => (new MatakuliahModel())->ditawarkan($akademik->tahunAjaran, $akademik->semester),
            'distribusi'     => $distribusi,
            'distribusiNilai'=> $distribusi,
        ]);
    }
}

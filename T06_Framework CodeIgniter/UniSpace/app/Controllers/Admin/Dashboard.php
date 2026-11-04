<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Models\KategoriModel;
use App\Models\PesanModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $fasilitasModel = new FasilitasModel();
        $kategoriModel  = new KategoriModel();
        $pesanModel     = new PesanModel();
        $userModel      = new UserModel();

        $kategoriList = $kategoriModel->withJumlahFasilitas();

        // Data untuk Chart.js (distribusi fasilitas per kategori)
        $chartLabels = [];
        $chartData   = [];
        foreach ($kategoriList as $k) {
            $chartLabels[] = $k['nama'];
            $chartData[]   = (int) $k['jumlah_fasilitas'];
        }

        return view('admin/dashboard', [
            'title'           => 'Dashboard Sarpras',
            'statistik'       => $fasilitasModel->statistik(),
            'totalPesan'      => $pesanModel->countAllResults(),
            'totalUsers'      => $userModel->countAllResults(),
            'chartLabels'     => json_encode($chartLabels),
            'chartData'       => json_encode($chartData),
            'pesanTerbaru'    => $pesanModel->withFasilitas()->orderBy('created_at', 'DESC')->findAll(5),
            'fasilitasBaru'   => $fasilitasModel->withKategori()->orderBy('created_at', 'DESC')->findAll(5),
        ]);
    }
}


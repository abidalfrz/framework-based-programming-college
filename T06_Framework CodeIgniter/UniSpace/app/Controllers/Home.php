<?php

namespace App\Controllers;

use App\Models\FasilitasModel;
use App\Models\KategoriModel;

class Home extends BaseController
{
    public function index(): string
    {
        $fasilitasModel = new FasilitasModel();
        $kategoriModel  = new KategoriModel();

        return view('pages/home', [
            'title'             => 'Katalog Fasilitas & Ruangan Kampus',
            'statistik'         => $fasilitasModel->statistik(),
            'kategoriList'      => $kategoriModel->withJumlahFasilitas(),
            'fasilitasUnggulan' => $fasilitasModel->withKategori()->orderBy('fasilitas.created_at', 'DESC')->findAll(6),
        ]);
    }
}

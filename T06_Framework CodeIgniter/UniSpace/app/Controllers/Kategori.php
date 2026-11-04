<?php

namespace App\Controllers;

use App\Models\FasilitasModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Kategori extends BaseController
{
    public function detail(string $slug): string
    {
        $kategoriModel  = new KategoriModel();
        $fasilitasModel = new FasilitasModel();

        $kategori = $kategoriModel->findBySlug($slug);
        if ($kategori === null) {
            throw PageNotFoundException::forPageNotFound('Kategori fasilitas tidak ditemukan.');
        }

        $fasilitas = $fasilitasModel->withKategori()
            ->where('fasilitas.kategori_id', $kategori['id'])
            ->orderBy('fasilitas.nama', 'ASC')
            ->paginate(9);

        return view('pages/kategori_detail', [
            'title'     => 'Kategori: ' . $kategori['nama'],
            'kategori'  => $kategori,
            'fasilitas' => $fasilitas,
            'pager'     => $fasilitasModel->pager,
        ]);
    }
}


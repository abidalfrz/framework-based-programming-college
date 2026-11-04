<?php

namespace App\Controllers;

use App\Models\FasilitasModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Direktori extends BaseController
{
    private FasilitasModel $fasilitasModel;
    private KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->fasilitasModel = new FasilitasModel();
        $this->kategoriModel  = new KategoriModel();
    }

    public function index(): string
    {
        $keyword    = trim((string) $this->request->getGet('q'));
        $kategori   = $this->request->getGet('kategori');
        $kategoriId = ctype_digit((string) $kategori) ? (int) $kategori : null;
        $status     = trim((string) $this->request->getGet('status'));

        $fasilitas = $this->fasilitasModel->cari($keyword, $kategoriId, $status ?: null, 9);

        return view('pages/direktori', [
            'title'        => 'Direktori Sarana & Fasilitas',
            'fasilitas'    => $fasilitas,
            'pager'        => $this->fasilitasModel->pager,
            'keyword'      => $keyword,
            'kategoriId'   => $kategoriId,
            'status'       => $status,
            'kategoriList' => $this->kategoriModel->orderBy('nama')->findAll(),
        ]);
    }

    public function detail(string $slug): string
    {
        $item = $this->fasilitasModel->detailBySlug($slug);

        if ($item === null && ctype_digit($slug)) {
            $item = $this->fasilitasModel->detailById((int) $slug);
        }

        if ($item === null) {
            throw PageNotFoundException::forPageNotFound('Fasilitas kampus tidak ditemukan.');
        }

        // Fasilitas terkait dalam kategori yang sama
        $terkait = $this->fasilitasModel->withKategori()
            ->where('fasilitas.kategori_id', $item['kategori_id'])
            ->where('fasilitas.id !=', $item['id'])
            ->orderBy('fasilitas.created_at', 'DESC')
            ->findAll(3);

        return view('pages/fasilitas_detail', [
            'title'     => $item['nama'],
            'fasilitas' => $item,
            'terkait'   => $terkait,
        ]);
    }
}


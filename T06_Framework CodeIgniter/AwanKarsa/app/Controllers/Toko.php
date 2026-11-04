<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Toko extends BaseController
{
    public function index(): string
    {
        $produk = new ProdukModel();

        return view('toko/beranda', [
            'title'    => 'Sewa Cloud untuk Proyek',
            'kategori' => (new KategoriModel())->denganJumlah(),
            'terlaris' => $produk->terlaris(4),
            'terbaru'  => (new ProdukModel())->withKategori()->where('produk.aktif', 1)->orderBy('produk.created_at', 'DESC')->findAll(4),
        ]);
    }

    public function katalog(): string
    {
        $q        = trim((string) $this->request->getGet('q'));
        $kategori = (string) $this->request->getGet('kategori');
        $urut     = (string) $this->request->getGet('urut');
        if (! array_key_exists($urut, ProdukModel::URUTAN)) {
            $urut = 'terbaru';
        }

        $model = new ProdukModel();

        return view('toko/katalog', [
            'title'         => 'Katalog Produk',
            'produk'        => $model->katalog($q, $kategori, $urut, 8),
            'pager'         => $model->pager,
            'kategori'      => (new KategoriModel())->denganJumlah(),
            'q'             => $q,
            'kategoriAktif' => $kategori,
            'urut'          => $urut,
        ]);
    }

    public function produk(string $slug): string
    {
        $model  = new ProdukModel();
        $produk = $model->bySlug($slug) ?? throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');

        return view('toko/produk', [
            'title'   => $produk['nama'],
            'produk'  => $produk,
            'terkait' => $model->terkait($produk),
        ]);
    }
}

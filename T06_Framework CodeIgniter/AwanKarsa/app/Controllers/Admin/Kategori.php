<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Kategori extends BaseController
{
    private KategoriModel $kategori;

    public function __construct()
    {
        $this->kategori = new KategoriModel();
    }

    public function index(): string
    {
        return view('admin/kategori/index', ['title' => 'Kategori', 'kategori' => $this->kategori->denganJumlah()]);
    }

    public function new(): string
    {
        return view('admin/kategori/form', ['title' => 'Tambah Kategori', 'kategori' => null]);
    }

    public function edit(int $id): string
    {
        return view('admin/kategori/form', ['title' => 'Edit Kategori', 'kategori' => $this->temukan($id)]);
    }

    public function create(): RedirectResponse
    {
        $data = $this->input();
        if ($this->kategori->insert($data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->kategori->errors());
        }

        return redirect()->to('/admin/kategori')->with('success', "Kategori {$data['nama']} ditambahkan.");
    }

    public function update(int $id): RedirectResponse
    {
        $this->temukan($id);
        $data = ['id' => $id] + $this->input();
        if ($this->kategori->update($id, $data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->kategori->errors());
        }

        return redirect()->to('/admin/kategori')->with('success', "Kategori {$data['nama']} diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $kategori = $this->temukan($id);
        $jumlah   = (new ProdukModel())->where('kategori_id', $id)->countAllResults();
        if ($jumlah > 0) {
            return redirect()->back()->with('error', "Kategori {$kategori['nama']} masih berisi {$jumlah} produk.");
        }

        $this->kategori->delete($id);

        return redirect()->to('/admin/kategori')->with('success', "Kategori {$kategori['nama']} dihapus.");
    }

    private function temukan(int $id): array
    {
        return $this->kategori->find($id) ?? throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
    }

    /** @return array{nama: string, ikon: string} */
    private function input(): array
    {
        return [
            'nama' => trim((string) $this->request->getPost('nama')),
            'ikon' => trim((string) $this->request->getPost('ikon')) ?: 'bag',
        ];
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Kategori extends BaseController
{
    private KategoriModel $model;

    public function __construct()
    {
        $this->model = new KategoriModel();
    }

    public function index(): string
    {
        return view('admin/kategori/index', [
            'title'        => 'Kelola Kategori Fasilitas',
            'kategoriList' => $this->model->withJumlahFasilitas(),
        ]);
    }

    public function new(): string
    {
        return view('admin/kategori/form', [
            'title'    => 'Tambah Kategori Fasilitas',
            'kategori' => null,
        ]);
    }

    public function create(): RedirectResponse
    {
        $data = $this->ambilInput();

        if (! $this->model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        $this->model->insert($data);

        return redirect()->to('/admin/kategori')->with('success', "Kategori '{$data['nama']}' berhasil ditambahkan.");
    }

    public function edit(int $id): string
    {
        return view('admin/kategori/form', [
            'title'    => 'Edit Kategori Fasilitas',
            'kategori' => $this->cariAtau404($id),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->cariAtau404($id);
        $data = ['id' => $id] + $this->ambilInput();

        if (! $this->model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        unset($data['id']);
        $this->model->update($id, $data);

        return redirect()->to('/admin/kategori')->with('success', "Kategori '{$data['nama']}' berhasil diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $kategori = $this->cariAtau404($id);

        // Integritas Relasi: Cek apakah masih ada fasilitas di kategori ini
        $jumlahFasilitas = (new FasilitasModel())->where('kategori_id', $id)->countAllResults();
        if ($jumlahFasilitas > 0) {
            return redirect()->to('/admin/kategori')->with('error', "Kategori '{$kategori['nama']}' tidak dapat dihapus karena masih memuat {$jumlahFasilitas} fasilitas.");
        }

        $this->model->delete($id);

        return redirect()->to('/admin/kategori')->with('success', "Kategori '{$kategori['nama']}' berhasil dihapus.");
    }

    // ------------------------------------------------------------------

    private function ambilInput(): array
    {
        $input = $this->request->getPost(['kode', 'nama', 'icon', 'deskripsi']);

        return array_map(static fn ($v) => is_string($v) ? trim($v) : $v, $input);
    }

    private function cariAtau404(int $id): array
    {
        $item = $this->model->find($id);
        if ($item === null) {
            throw PageNotFoundException::forPageNotFound('Kategori fasilitas tidak ditemukan.');
        }

        return $item;
    }
}


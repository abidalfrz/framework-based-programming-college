<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesanModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Pesan extends BaseController
{
    private PesanModel $model;

    public function __construct()
    {
        $this->model = new PesanModel();
    }

    public function index(): string
    {
        return view('admin/pesan/index', [
            'title'     => 'Kotak Masuk Pesan & Aduan Fasilitas',
            'pesanList' => $this->model->withFasilitas()->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function updateStatus(int $id): RedirectResponse
    {
        $status = $this->request->getPost('status');
        if (in_array($status, ['baru', 'dibaca', 'selesai'], true)) {
            $this->model->update($id, ['status' => $status]);
        }

        return redirect()->to('/admin/pesan')->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function delete(int $id): RedirectResponse
    {
        $item = $this->model->find($id);
        if ($item === null) {
            throw PageNotFoundException::forPageNotFound('Pesan tidak ditemukan.');
        }

        $this->model->delete($id);

        return redirect()->to('/admin/pesan')->with('success', 'Pesan berhasil dihapus.');
    }
}


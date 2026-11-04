<?php

namespace App\Controllers;

use App\Models\FasilitasModel;
use App\Models\PesanModel;
use CodeIgniter\HTTP\RedirectResponse;

class Pages extends BaseController
{
    public function about(): string
    {
        return view('pages/about', [
            'title' => 'Tentang UniSpace Kampus',
        ]);
    }

    public function contact(): string
    {
        $fasilitasModel = new FasilitasModel();

        return view('pages/contact', [
            'title'         => 'Kontak & Bantuan Sarpras',
            'fasilitasOpt'  => $fasilitasModel->orderBy('nama')->findAll(),
            'preselectedId' => $this->request->getGet('fasilitas_id'),
        ]);
    }

    public function kirimPesan(): RedirectResponse
    {
        $model = new PesanModel();

        $rules = [
            'nama'         => 'required|min_length[3]|max_length[100]',
            'email'        => 'required|valid_email|max_length[100]',
            'no_hp'        => 'permit_empty|min_length[8]|max_length[20]',
            'fasilitas_id' => 'permit_empty',
            'subjek'       => 'required|min_length[5]|max_length[150]',
            'isi'          => 'required|min_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fasilitasId = $this->request->getPost('fasilitas_id');

        $data = [
            'nama'         => trim((string) $this->request->getPost('nama')),
            'email'        => trim((string) $this->request->getPost('email')),
            'no_hp'        => trim((string) $this->request->getPost('no_hp')),
            'fasilitas_id' => ! empty($fasilitasId) ? (int) $fasilitasId : null,
            'subjek'       => trim((string) $this->request->getPost('subjek')),
            'isi'          => trim((string) $this->request->getPost('isi')),
            'status'       => 'baru',
        ];

        $model->insert($data);

        return redirect()->to('/contact')->with('success', 'Pesan / aduan Anda telah berhasil dikirimkan ke petugas Sarpras. Terima kasih!');
    }
}


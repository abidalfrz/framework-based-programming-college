<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index(): string
    {
        return view('admin/users/index', [
            'title' => 'Kelola Akun Petugas Sarpras',
            'users' => $this->model->orderBy('role', 'ASC')->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function new(): string
    {
        return view('admin/users/form', [
            'title' => 'Tambah Petugas Baru',
            'user'  => null,
        ]);
    }

    public function create(): RedirectResponse
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => 'required|alpha_dash|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,staff]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'          => trim((string) $this->request->getPost('nama')),
            'username'      => trim((string) $this->request->getPost('username')),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
        ]);

        return redirect()->to('/admin/users')->with('success', 'Akun petugas berhasil dibuat.');
    }

    public function edit(int $id): string
    {
        return view('admin/users/form', [
            'title' => 'Edit Akun Petugas',
            'user'  => $this->cariAtau404($id),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $user = $this->cariAtau404($id);

        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => "required|alpha_dash|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'password' => 'permit_empty|min_length[6]',
            'role'     => 'required|in_list[admin,staff]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'     => trim((string) $this->request->getPost('nama')),
            'username' => trim((string) $this->request->getPost('username')),
            'role'     => $this->request->getPost('role'),
        ];

        $pass = (string) $this->request->getPost('password');
        if ($pass !== '') {
            $data['password_hash'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        $this->model->update($id, $data);

        return redirect()->to('/admin/users')->with('success', "Akun '{$user['nama']}' berhasil diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $user = $this->cariAtau404($id);

        // Jangan izinkan admin menghapus akunnya sendiri
        if ((int) session('user_id') === $id) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $this->model->delete($id);

        return redirect()->to('/admin/users')->with('success', "Akun '{$user['nama']}' berhasil dihapus.");
    }

    private function cariAtau404(int $id): array
    {
        $item = $this->model->find($id);
        if ($item === null) {
            throw PageNotFoundException::forPageNotFound('Pengguna tidak ditemukan.');
        }

        return $item;
    }
}


<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string
    {
        return view('auth/login', ['title' => 'Masuk']);
    }

    public function attempt(): RedirectResponse
    {
        $rules = [
            'email'    => ['label' => 'Email', 'rules' => 'required|valid_email'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->verifikasi((string) $this->request->getPost('email'), (string) $this->request->getPost('password'));
        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        return $this->masuk($user, 'Selamat datang kembali, ' . $user['nama'] . '!');
    }

    public function daftar(): string
    {
        return view('auth/daftar', ['title' => 'Daftar Akun']);
    }

    public function simpan(): RedirectResponse
    {
        $rules = [
            'nama'       => ['label' => 'Nama', 'rules' => 'required|min_length[3]|max_length[100]'],
            'email'      => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]'],
            'telepon'    => ['label' => 'Telepon', 'rules' => 'required|regex_match[/^(\+62|0)8[0-9]{8,12}$/]'],
            'alamat'     => ['label' => 'Alamat', 'rules' => 'permit_empty|max_length[500]'],
            'password'   => ['label' => 'Password', 'rules' => 'required|min_length[8]|max_length[72]'],
            'konfirmasi' => ['label' => 'Konfirmasi password', 'rules' => 'required|matches[password]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->validator->getValidated();
        $users = new UserModel();
        $id    = $users->insert([
            'nama'          => trim($data['nama']),
            'email'         => strtolower(trim($data['email'])),
            'telepon'       => trim($data['telepon']),
            'alamat'        => trim($data['alamat'] ?? ''),
            'role'          => 'pembeli',
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        return $this->masuk($users->find($id), 'Akun berhasil dibuat. Selamat menggunakan AwanKarsa!');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/');
    }

    private function masuk(array $user, string $pesan): RedirectResponse
    {
        $session = session();
        $tujuan  = $session->get('url_tujuan');
        $session->regenerate(true);
        $session->remove('url_tujuan');
        $session->set([
            'isLoggedIn' => true,
            'user_id'    => (int) $user['id'],
            'nama'       => $user['nama'],
            'email'      => $user['email'],
            'role'       => $user['role'],
        ]);

        // Hanya URL milik situs ini yang dipakai sebagai tujuan (mencegah open redirect).
        $default = $user['role'] === 'admin' ? site_url('admin') : site_url('/');
        $aman    = is_string($tujuan) && str_starts_with($tujuan, site_url('/'));

        return redirect()->to($aman ? $tujuan : $default)->with('success', $pesan);
    }
}

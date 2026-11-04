<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string
    {
        return view('auth/login', ['title' => 'Login Petugas Sarpras']);
    }

    public function attempt(): RedirectResponse
    {
        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->verifikasi(
            (string) $this->request->getPost('username'),
            (string) $this->request->getPost('password')
        );

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        $session = session();
        $session->regenerate(true);
        $session->set([
            'isLoggedIn' => true,
            'user_id'    => (int) $user['id'],
            'nama'       => $user['nama'],
            'username'   => $user['username'],
            'role'       => $user['role'],
        ]);

        return redirect()->to('/admin')->with('success', 'Selamat datang kembali, ' . $user['nama'] . '!');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}


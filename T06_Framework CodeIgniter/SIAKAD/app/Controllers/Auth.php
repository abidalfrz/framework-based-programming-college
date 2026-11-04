<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string
    {
        return view('auth/login', ['title' => 'Login']);
    }

    public function attempt(): RedirectResponse
    {
        $rules = [
            'username' => ['label' => 'NIM / NIDN / Username', 'rules' => 'required|max_length[30]'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->verifikasi(
            trim((string) $this->request->getPost('username')),
            trim((string) $this->request->getPost('password')),
        );

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        // profil_id = id di tabel dosen / mahasiswa, dipakai untuk membatasi data milik sendiri.
        $profil = match ($user['role']) {
            'dosen'     => (new DosenModel())->where('user_id', $user['id'])->first(),
            'mahasiswa' => (new MahasiswaModel())->where('user_id', $user['id'])->first(),
            default     => null,
        };

        $session = session();
        $session->regenerate(true);
        $session->set([
            'isLoggedIn' => true,
            'user_id'    => (int) $user['id'],
            'profil_id'  => $profil === null ? null : (int) $profil['id'],
            'nama'       => $user['nama'],
            'username'   => $user['username'],
            'role'       => $user['role'],
        ]);

        return redirect()->to('/')->with('success', 'Selamat datang, ' . $user['nama'] . '!');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    public function password(): string
    {
        return view('auth/password', ['title' => 'Ganti Password']);
    }

    public function gantiPassword(): RedirectResponse
    {
        $rules = [
            'password_lama' => ['label' => 'Password lama', 'rules' => 'required'],
            'password_baru' => ['label' => 'Password baru', 'rules' => 'required|min_length[8]|max_length[72]'],
            'konfirmasi'    => ['label' => 'Konfirmasi password', 'rules' => 'required|matches[password_baru]'],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();
        $user  = $users->find((int) session('user_id'));

        if ($user === null || ! password_verify((string) $this->request->getPost('password_lama'), $user['password_hash'])) {
            return redirect()->back()->with('errors', ['password_lama' => 'Password lama tidak sesuai.']);
        }

        $users->update($user['id'], [
            'password_hash' => password_hash((string) $this->request->getPost('password_baru'), PASSWORD_DEFAULT),
        ]);
        session()->regenerate(true);

        return redirect()->to('/')->with('success', 'Password berhasil diganti.');
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    /** Setiap role punya halaman awal sendiri. */
    public function index(): RedirectResponse
    {
        // Pesan flash (mis. "Akses ditolak") harus tetap terbawa ke halaman tujuan.
        // keepFlashdata() gagal total bila salah satu key tidak ada, jadi hanya key yang ada yang diteruskan.
        $session = session();
        $ada     = array_values(array_filter(['success', 'error'], static fn (string $k): bool => $session->has($k)));
        if ($ada !== []) {
            $session->keepFlashdata($ada);
        }

        return redirect()->to(match (session('role')) {
            'admin' => '/admin',
            'dosen' => '/dosen',
            default => '/mahasiswa',
        });
    }
}

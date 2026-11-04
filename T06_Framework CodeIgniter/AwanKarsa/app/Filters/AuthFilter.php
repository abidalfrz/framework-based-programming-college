<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Menolak akses jika belum login. URL tujuan disimpan agar setelah login
 * pembeli kembali ke halaman semula (mis. checkout).
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            if (strtolower($request->getMethod()) === 'get') {
                session()->set('url_tujuan', current_url());
            }

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

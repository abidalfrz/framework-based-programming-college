<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Membatasi akses rute berdasarkan role tertentu (contoh: 'filter' => 'role:admin')
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');

        if ($arguments !== null && ! in_array($role, $arguments, true)) {
            return redirect()->to('/admin')->with('error', 'Akses ditolak: Fitur ini hanya dapat diakses oleh peran: ' . implode(', ', $arguments) . '.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}


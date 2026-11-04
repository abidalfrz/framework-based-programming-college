<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Membatasi route berdasarkan role, contoh pemakaian: 'filter' => 'role:admin'.
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');

        if ($arguments !== null && ! in_array($role, $arguments, true)) {
            return redirect()->to('/')->with('error', 'Akses ditolak: fitur ini hanya untuk role ' . implode(', ', $arguments) . '.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

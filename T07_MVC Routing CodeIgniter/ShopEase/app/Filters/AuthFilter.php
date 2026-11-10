<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('logged_in')) {
            return redirect()->to('/login');
        }
        $user = (new \App\Models\UserModel())->select('id, role, is_active')->find((int) $session->get('user_id'));
        if (! $user || ! $user['is_active'] || $user['role'] !== $session->get('role')) {
            $session->destroy();
            return redirect()->to('/login');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}

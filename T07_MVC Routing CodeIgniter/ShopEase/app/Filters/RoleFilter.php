<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in') || ! in_array(session()->get('role'), $arguments ?? [], true)) {
            return service('response')->setStatusCode(403)->setBody('Forbidden');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}

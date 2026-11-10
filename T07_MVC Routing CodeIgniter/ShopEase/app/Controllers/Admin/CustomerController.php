<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    public function index(): string
    {
        $model = (new UserModel())->select('id, name, email, is_active, created_at')->where('role', 'customer');
        $query = trim((string) $this->request->getGet('q'));
        if ($query !== '') { $model->groupStart()->like('name', mb_substr($query, 0, 100))->orLike('email', mb_substr($query, 0, 100))->groupEnd(); }
        $customers = $model->orderBy('name')->paginate(12);
        return view('admin/customers', ['title' => 'Customers — ShopEase', 'customers' => $customers, 'pager' => $model->pager, 'query' => $query]);
    }
}

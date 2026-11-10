<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $sales = $db->table('orders')->select('COALESCE(SUM(total),0) AS total', false)->whereIn('order_status', ['processing', 'shipped', 'completed'])->get()->getRowArray()['total'];
        $recent = $db->table('orders')->select('id, order_number, order_status, total')->orderBy('id', 'DESC')->limit(5)->get()->getResultArray();
        return view('admin/dashboard', ['title' => 'Dashboard — ShopEase', 'sales' => $sales, 'recent' => $recent, 'products' => $db->table('products')->countAllResults(), 'customers' => $db->table('users')->where('role', 'customer')->countAllResults(), 'orders' => $db->table('orders')->countAllResults(), 'pending' => $db->table('payments')->join('orders', 'orders.id = payments.order_id')->where('payments.payment_status', 'submitted')->where('orders.order_status', 'waiting_verification')->countAllResults()]);
    }
}

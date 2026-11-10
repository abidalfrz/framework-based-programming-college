<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\PaymentModel;
use App\Models\ShipmentModel;
use App\Models\SettingModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class OrderController extends BaseController
{
    public function index(): string
    {
        $orders = (new OrderModel())->where('user_id', (int) session('user_id'))->orderBy('id', 'DESC')->findAll();
        return view('orders/index', ['title' => 'Your orders — ShopEase', 'orders' => $orders]);
    }

    public function show(int $id): string
    {
        $order = (new OrderModel())->where('user_id', (int) session('user_id'))->find($id);
        if (! $order) {
            throw PageNotFoundException::forPageNotFound();
        }
        return view('orders/show', ['title' => 'Order ' . $order['order_number'], 'order' => $order, 'items' => (new OrderItemModel())->where('order_id', $id)->findAll(), 'payment' => (new PaymentModel())->where('order_id', $id)->first(), 'shipment' => (new ShipmentModel())->where('order_id', $id)->first(), 'settings' => (new SettingModel())->first()]);
    }
}

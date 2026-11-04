<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\OrderStatus;
use App\Models\OrderModel;
use App\Models\ProdukModel;

class Dashboard extends BaseController
{
    private const STOK_MENIPIS = 5;

    public function index(): string
    {
        $orders    = new OrderModel();
        $perStatus = $orders->jumlahPerStatus();
        $omzet     = $orders->omzetHarian(7);

        return view('admin/dashboard', [
            'title'      => 'Dashboard',
            'perStatus'  => $perStatus,
            'perluAksi'  => ($perStatus[OrderStatus::MENUNGGU_VERIFIKASI] ?? 0) + ($perStatus[OrderStatus::DIPROSES] ?? 0),
            'omzet'      => $omzet,
            'omzetTotal' => (int) ($orders->selectSum('total')->whereIn('status', ['diproses', 'dikirim', 'selesai'])->first()['total'] ?? 0),
            'menipis'    => (new ProdukModel())->where('stok <=', self::STOK_MENIPIS)->where('aktif', 1)->orderBy('stok')->findAll(),
            'terbaru'    => (new OrderModel())->withPembeli()->orderBy('orders.created_at', 'DESC')->findAll(5),
            'terlaris'   => (new ProdukModel())->terlaris(5),
        ]);
    }
}

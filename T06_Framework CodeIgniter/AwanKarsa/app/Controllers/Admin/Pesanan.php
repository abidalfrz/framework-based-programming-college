<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\OrderStatus;
use App\Libraries\PesananService;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

class Pesanan extends BaseController
{
    public function index(): string
    {
        $status = (string) $this->request->getGet('status');
        if (! in_array($status, OrderStatus::semua(), true)) {
            $status = '';
        }
        $orders = new OrderModel();

        return view('admin/pesanan/index', [
            'title'     => 'Pesanan',
            'pesanan'   => $orders->daftarAdmin($status, 10),
            'pager'     => $orders->pager,
            'status'    => $status,
            'perStatus' => (new OrderModel())->jumlahPerStatus(),
        ]);
    }

    public function show(string $kode): string
    {
        $order = $this->temukan($kode);

        return view('admin/pesanan/show', [
            'title'  => 'Pesanan ' . $order['kode'],
            'order'  => $order,
            'items'  => (new OrderItemModel())->untukOrder((int) $order['id']),
            'tujuan' => OrderStatus::tujuan($order['status'], 'admin'),
        ]);
    }

    public function status(string $kode): RedirectResponse
    {
        $order = $this->temukan($kode);
        $ke    = (string) $this->request->getPost('status');

        $rules = [
            'status'        => ['label' => 'Status', 'rules' => 'required|in_list[' . implode(',', OrderStatus::semua()) . ']'],
            'resi'          => ['label' => 'Referensi layanan', 'rules' => 'permit_empty|alpha_numeric|max_length[40]'],
            'catatan_admin' => ['label' => 'Catatan', 'rules' => 'permit_empty|max_length[255]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tambahan = [];
        if ($ke === OrderStatus::DIKIRIM) {
            $tambahan['resi'] = strtoupper(trim((string) $this->request->getPost('resi')));
        }
        $catatan = trim((string) $this->request->getPost('catatan_admin'));
        if ($catatan !== '') {
            $tambahan['catatan_admin'] = $catatan;
        }

        try {
            (new PesananService())->ubahStatus($order, $ke, 'admin', $tambahan);
        } catch (DomainException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/admin/pesanan/' . $order['kode'])
            ->with('success', "Status {$order['kode']} diubah menjadi " . OrderStatus::label($ke) . '.');
    }

    private function temukan(string $kode): array
    {
        return (new OrderModel())->withPembeli()->where('orders.kode', $kode)->first()
            ?? throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
    }
}

<?php

namespace App\Controllers;

use App\Libraries\OrderStatus;
use App\Libraries\PesananService;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

/**
 * Riwayat pesanan milik pembeli yang sedang login.
 */
class Pesanan extends BaseController
{
    public const FOLDER_BUKTI = WRITEPATH . 'uploads/bukti/';

    public function index(): string
    {
        return view('pesanan/index', [
            'title'   => 'Pesanan Saya',
            'pesanan' => (new OrderModel())->milik((int) session('user_id')),
        ]);
    }

    public function show(string $kode): string
    {
        $order = $this->milikSaya($kode);

        return view('pesanan/show', [
            'title' => 'Pesanan ' . $order['kode'],
            'order' => $order,
            'items' => (new OrderItemModel())->untukOrder((int) $order['id']),
        ]);
    }

    public function uploadBukti(string $kode): RedirectResponse
    {
        $order = $this->milikSaya($kode);

        $rules = ['bukti' => [
            'label' => 'Bukti pembayaran',
            'rules' => 'uploaded[bukti]|is_image[bukti]|mime_in[bukti,image/jpeg,image/png,image/webp]|ext_in[bukti,jpg,jpeg,png,webp]|max_size[bukti,2048]',
        ]];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('bukti');
        $nama = $file->getRandomName();
        $file->move(self::FOLDER_BUKTI, $nama);

        try {
            (new PesananService())->ubahStatus($order, OrderStatus::MENUNGGU_VERIFIKASI, 'pembeli', ['bukti_bayar' => $nama, 'catatan_admin' => null]);
        } catch (DomainException $e) {
            @unlink(self::FOLDER_BUKTI . $nama); // status tidak berubah -> file baru tidak dipakai

            return redirect()->back()->with('error', $e->getMessage());
        }

        // Bukti lama (yang sebelumnya ditolak admin) tidak diperlukan lagi.
        if (! empty($order['bukti_bayar'])) {
            @unlink(self::FOLDER_BUKTI . basename($order['bukti_bayar']));
        }

        return redirect()->to('/pesanan/' . $order['kode'])->with('success', 'Bukti pembayaran terkirim, menunggu verifikasi admin.');
    }

    public function batal(string $kode): RedirectResponse
    {
        return $this->ubah($kode, OrderStatus::DIBATALKAN, 'Pesanan dibatalkan dan stok dikembalikan.');
    }

    public function diterima(string $kode): RedirectResponse
    {
        return $this->ubah($kode, OrderStatus::SELESAI, 'Terima kasih! Pesanan ditandai selesai.');
    }

    private function ubah(string $kode, string $ke, string $pesan): RedirectResponse
    {
        $order = $this->milikSaya($kode);

        try {
            (new PesananService())->ubahStatus($order, $ke, 'pembeli');
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->to('/pesanan/' . $order['kode'])->with('success', $pesan);
    }

    private function milikSaya(string $kode): array
    {
        return (new OrderModel())->byKodeMilik($kode, (int) session('user_id'))
            ?? throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
    }
}

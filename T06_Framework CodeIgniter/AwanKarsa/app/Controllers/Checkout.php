<?php

namespace App\Controllers;

use App\Libraries\Keranjang;
use App\Libraries\PesananService;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

class Checkout extends BaseController
{
    public function index(): RedirectResponse|string
    {
        $keranjang = new Keranjang();
        $isi       = $keranjang->isi();

        if ($isi === []) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang masih kosong.');
        }
        if (array_filter(array_column($isi, 'masalah')) !== []) {
            return redirect()->to('/keranjang')->with('error', 'Perbaiki item yang bermasalah sebelum checkout.');
        }

        return view('checkout/index', [
            'title'     => 'Checkout',
            'isi'       => $isi,
            'ringkasan' => $keranjang->ringkasan($isi),
            'user'      => (new UserModel())->find((int) session('user_id')),
        ]);
    }

    public function proses(): RedirectResponse
    {
        $rules = [
            'nama_penerima' => ['label' => 'Nama pelanggan', 'rules' => 'required|min_length[3]|max_length[100]'],
            'telepon'       => ['label' => 'Telepon', 'rules' => 'required|regex_match[/^(\+62|0)8[0-9]{8,12}$/]'],
            'durasi_bulan'  => ['label' => 'Durasi sewa', 'rules' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[12]'],
            'catatan'       => ['label' => 'Catatan', 'rules' => 'permit_empty|max_length[255]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = array_map(static fn ($v): string => trim((string) $v), $this->validator->getValidated() + ['catatan' => '', 'alamat' => '']);

        try {
            $order = (new PesananService())->checkout((int) session('user_id'), new Keranjang(), $data);
        } catch (DomainException $e) {
            return redirect()->to('/keranjang')->with('error', $e->getMessage());
        }

        return redirect()->to('/pesanan/' . $order['kode'])
            ->with('success', "Pesanan {$order['kode']} dibuat. Silakan transfer dan unggah bukti pembayaran.");
    }
}

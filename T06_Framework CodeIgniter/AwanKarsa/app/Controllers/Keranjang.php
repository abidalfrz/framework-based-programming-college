<?php

namespace App\Controllers;

use App\Libraries\Keranjang as KeranjangBelanja;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

class Keranjang extends BaseController
{
    public function index(): string
    {
        $keranjang = new KeranjangBelanja();
        $isi       = $keranjang->isi();

        return view('keranjang/index', [
            'title'     => 'Keranjang',
            'isi'       => $isi,
            'ringkasan' => $keranjang->ringkasan($isi),
            'adaMasalah' => array_filter(array_column($isi, 'masalah')) !== [],
        ]);
    }

    public function tambah(): RedirectResponse
    {
        try {
            $produk = (new KeranjangBelanja())->tambah(
                (int) $this->request->getPost('produk_id'),
                (int) ($this->request->getPost('qty') ?? 1),
            );
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', "{$produk['nama']} masuk ke keranjang.");
    }

    public function ubah(int $produkId): RedirectResponse
    {
        try {
            (new KeranjangBelanja())->ubah($produkId, (int) $this->request->getPost('qty'));
        } catch (DomainException $e) {
            return redirect()->to('/keranjang')->with('error', $e->getMessage());
        }

        return redirect()->to('/keranjang')->with('success', 'Jumlah produk diperbarui.');
    }

    public function hapus(int $produkId): RedirectResponse
    {
        (new KeranjangBelanja())->hapus($produkId);

        return redirect()->to('/keranjang')->with('success', 'Produk dihapus dari keranjang.');
    }
}

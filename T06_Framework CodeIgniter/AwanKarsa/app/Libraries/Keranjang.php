<?php

namespace App\Libraries;

use App\Models\ProdukModel;
use CodeIgniter\Session\Session;
use Config\Toko;
use DomainException;

/**
 * Keranjang belanja berbasis session. Session hanya menyimpan [produk_id => qty];
 * harga & stok selalu dibaca ulang dari database agar tidak bisa dimanipulasi.
 */
class Keranjang
{
    private const KEY = 'keranjang';

    private Session $session;
    private ?ProdukModel $produk;
    private Toko $toko;

    public function __construct(?Session $session = null, ?ProdukModel $produk = null, ?Toko $toko = null)
    {
        $this->session = $session ?? session();
        $this->produk  = $produk;
        $this->toko    = $toko ?? config(Toko::class);
    }

    /** @return array<int, int> produk_id => qty */
    public function mentah(): array
    {
        return (array) ($this->session->get(self::KEY) ?? []);
    }

    public function jumlahItem(): int
    {
        return array_sum($this->mentah());
    }

    /** @return array<string, mixed> produk yang ditambahkan */
    public function tambah(int $produkId, int $qty): array
    {
        $produk = $this->produkAktif($produkId);
        $isi    = $this->mentah();
        $baru   = ($isi[$produkId] ?? 0) + max(1, $qty);

        $this->validasiQty($produk, $baru);
        $isi[$produkId] = $baru;
        $this->session->set(self::KEY, $isi);

        return $produk;
    }

    public function ubah(int $produkId, int $qty): void
    {
        $isi = $this->mentah();
        if (! isset($isi[$produkId])) {
            throw new DomainException('Produk tidak ada di keranjang.');
        }
        if ($qty <= 0) {
            $this->hapus($produkId);

            return;
        }

        $this->validasiQty($this->produkAktif($produkId), $qty);
        $isi[$produkId] = $qty;
        $this->session->set(self::KEY, $isi);
    }

    public function hapus(int $produkId): void
    {
        $isi = $this->mentah();
        unset($isi[$produkId]);
        $this->session->set(self::KEY, $isi);
    }

    public function kosongkan(): void
    {
        $this->session->remove(self::KEY);
    }

    /**
     * Isi keranjang lengkap dengan data produk terbaru dari database.
     *
     * @return list<array<string, mixed>> setiap baris + qty, subtotal, masalah (null bila aman)
     */
    public function isi(): array
    {
        $isi = $this->mentah();
        if ($isi === []) {
            return [];
        }

        $baris = [];
        foreach ($this->produk()->withKategori()->whereIn('produk.id', array_keys($isi))->findAll() as $p) {
            $qty     = $isi[(int) $p['id']];
            $baris[] = $p + [
                'qty'      => $qty,
                'subtotal' => (int) $p['harga'] * $qty,
                'masalah'  => $this->masalah($p, $qty),
            ];
        }

        return $baris;
    }

    /**
     * @param list<array<string, mixed>> $isi hasil isi()
     *
     * @return array{subtotal: int, ongkir: int, total: int, kurang_gratis: int}
     */
    public function ringkasan(array $isi): array
    {
        $subtotal = array_sum(array_column($isi, 'subtotal'));

        return self::hitungOngkir($subtotal, $this->toko);
    }

    /** @return array{subtotal: int, ongkir: int, total: int, kurang_gratis: int} */
    public static function hitungOngkir(int $subtotal, Toko $toko): array
    {
        $ongkir = 0;

        return [
            'subtotal'      => $subtotal,
            'ongkir'        => $ongkir,
            'total'         => $subtotal + $ongkir,
            'kurang_gratis' => 0,
        ];
    }

    private function produk(): ProdukModel
    {
        return $this->produk ??= model(ProdukModel::class);
    }

    private function produkAktif(int $produkId): array
    {
        $produk = $this->produk()->find($produkId);
        if ($produk === null || ! (int) $produk['aktif']) {
            throw new DomainException('Produk tidak tersedia.');
        }

        return $produk;
    }

    private function validasiQty(array $produk, int $qty): void
    {
        $masalah = $this->masalah($produk, $qty);
        if ($masalah !== null) {
            throw new DomainException($masalah);
        }
    }

    private function masalah(array $produk, int $qty): ?string
    {
        if (! (int) $produk['aktif']) {
            return "{$produk['nama']} sudah tidak tersedia.";
        }
        if ((int) $produk['stok'] === 0) {
            return "{$produk['nama']} sedang habis.";
        }
        if ($qty > (int) $produk['stok']) {
            return "Stok {$produk['nama']} hanya tersisa {$produk['stok']}.";
        }
        if ($qty > $this->toko->maksQtyPerProduk) {
            return "Maksimal {$this->toko->maksQtyPerProduk} unit per paket.";
        }

        return null;
    }
}

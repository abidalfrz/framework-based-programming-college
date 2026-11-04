<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Pengaturan toko: ongkir, batas pembelian, dan rekening pembayaran.
 */
class Toko extends BaseConfig
{
    public string $nama = 'AwanKarsa';

    /** Ongkir flat (Rupiah) bila belanja di bawah batas gratis ongkir. */
    public int $ongkir = 15000;

    /** Belanja minimal (Rupiah) untuk gratis ongkir. */
    public int $gratisOngkirMinimal = 200000;

    /** Jumlah maksimal per produk dalam satu pesanan. */
    public int $maksQtyPerProduk = 10;

    /** Batas waktu upload bukti bayar (jam) — ditampilkan ke pembeli. */
    public int $batasBayarJam = 24;

    /** @var list<array{bank: string, nomor: string, atas_nama: string}> */
    public array $rekening = [
        ['bank' => 'BNI', 'nomor' => '0123456789', 'atas_nama' => 'AwanKarsa'],
        ['bank' => 'BCA', 'nomor' => '9876543210', 'atas_nama' => 'AwanKarsa'],
    ];
}

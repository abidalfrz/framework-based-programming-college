<?php

namespace Tests\Unit;

use App\Libraries\Keranjang;
use App\Libraries\OrderStatus;
use App\Libraries\PesananService;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Toko;

/**
 * @internal
 */
final class OrderStatusTest extends CIUnitTestCase
{
    public function testPembeliHanyaBolehUploadBuktiAtauBatalSaatMenungguPembayaran(): void
    {
        $this->assertSame(
            [OrderStatus::MENUNGGU_VERIFIKASI, OrderStatus::DIBATALKAN],
            OrderStatus::tujuan(OrderStatus::MENUNGGU_PEMBAYARAN, 'pembeli'),
        );
        $this->assertFalse(OrderStatus::izinkan(OrderStatus::MENUNGGU_VERIFIKASI, OrderStatus::DIBATALKAN, 'pembeli'));
    }

    public function testAdminMemverifikasiAtauMenolakPembayaran(): void
    {
        $this->assertTrue(OrderStatus::izinkan(OrderStatus::MENUNGGU_VERIFIKASI, OrderStatus::DIPROSES, 'admin'));
        $this->assertTrue(OrderStatus::izinkan(OrderStatus::MENUNGGU_VERIFIKASI, OrderStatus::MENUNGGU_PEMBAYARAN, 'admin'));
        $this->assertFalse(OrderStatus::izinkan(OrderStatus::MENUNGGU_PEMBAYARAN, OrderStatus::MENUNGGU_VERIFIKASI, 'admin'));
    }

    public function testTidakBolehMelompatiStatus(): void
    {
        $this->assertFalse(OrderStatus::izinkan(OrderStatus::MENUNGGU_PEMBAYARAN, OrderStatus::DIKIRIM, 'admin'));
        $this->assertFalse(OrderStatus::izinkan(OrderStatus::DIPROSES, OrderStatus::SELESAI, 'admin'));
    }

    public function testStatusAkhirTidakPunyaTujuan(): void
    {
        $this->assertSame([], OrderStatus::tujuan(OrderStatus::SELESAI, 'admin'));
        $this->assertSame([], OrderStatus::tujuan(OrderStatus::DIBATALKAN, 'admin'));
    }

    public function testPembatalanDanSewaSelesaiMengembalikanStok(): void
    {
        $this->assertTrue(OrderStatus::kembalikanStok(OrderStatus::DIBATALKAN));
        $this->assertTrue(OrderStatus::kembalikanStok(OrderStatus::SELESAI));
        $this->assertFalse(OrderStatus::izinkan(OrderStatus::DIKIRIM, OrderStatus::SELESAI, 'pembeli'));
    }

    public function testHargaSewaSesuaiDurasi(): void
    {
        $this->assertSame(297000, PesananService::totalSewa(99000, '3'));
        $this->expectException(\DomainException::class);
        PesananService::totalSewa(99000, '13');
    }

    public function testSewaTidakDikenakanOngkir(): void
    {
        $ringkasan = Keranjang::hitungOngkir(199999, new Toko());
        $this->assertSame(0, $ringkasan['ongkir']);
        $this->assertSame(199999, $ringkasan['total']);
    }
}

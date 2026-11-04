<?php

namespace Tests\Unit;

use App\Libraries\Keranjang;
use App\Models\ProdukModel;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Toko;
use DomainException;

/** Cart behavior with a product repository mock; no MySQL claim is made here. */
final class KeranjangTest extends CIUnitTestCase
{
    private function cart(array $product): Keranjang
    {
        $model = $this->createMock(ProdukModel::class);
        $model->method('find')->willReturn($product);
        return new Keranjang(session(), $model, new Toko());
    }

    public function testAccumulatedQuantityCannotExceedStock(): void
    {
        $cart = $this->cart(['id' => 1, 'nama' => 'Buku', 'aktif' => 1, 'stok' => 3]);
        $cart->tambah(1, 2);
        try {
            $cart->tambah(1, 2);
            $this->fail('Stock exceeded.');
        } catch (DomainException $error) {
            $this->assertStringContainsString('tersisa 3', $error->getMessage());
        }
        $this->assertSame([1 => 2], $cart->mentah());
    }

    public function testPerProductLimitIsEnforced(): void
    {
        $cart = $this->cart(['id' => 1, 'nama' => 'Buku', 'aktif' => 1, 'stok' => 100]);
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Maksimal 10');
        $cart->tambah(1, 11);
    }

    public function testInactiveProductCannotBeAdded(): void
    {
        $cart = $this->cart(['id' => 1, 'nama' => 'Buku', 'aktif' => 0, 'stok' => 100]);
        $this->expectException(DomainException::class);
        $cart->tambah(1, 1);
    }

    public function testZeroQuantityRemovesAnItemAndKeepsOtherItems(): void
    {
        $cart = $this->cart(['id' => 1, 'nama' => 'Buku', 'aktif' => 1, 'stok' => 100]);
        $cart->tambah(1, 2);
        $cart->tambah(2, 3);
        $cart->ubah(1, 0);
        $this->assertSame([2 => 3], $cart->mentah());
        $this->assertSame(3, $cart->jumlahItem());
        $cart->kosongkan();
        $this->assertSame([], $cart->mentah());
    }
}

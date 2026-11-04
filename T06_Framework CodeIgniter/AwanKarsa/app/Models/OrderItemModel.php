<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table         = 'order_items';
    protected $returnType    = 'array';
    protected $allowedFields = ['order_id', 'produk_id', 'nama_produk', 'harga', 'qty', 'subtotal'];

    /** Item pesanan + slug/foto produk saat ini untuk tautan & gambar. */
    public function untukOrder(int $orderId): array
    {
        return $this->select('order_items.*, p.slug, p.foto, k.ikon')
            ->join('produk p', 'p.id = order_items.produk_id')
            ->join('kategori k', 'k.id = p.kategori_id')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    /** Pilihan urutan katalog: kunci query string => kolom ORDER BY. */
    public const URUTAN = [
        'terbaru'  => ['produk.created_at', 'DESC', 'Terbaru'],
        'termurah' => ['produk.harga', 'ASC', 'Harga terendah'],
        'termahal' => ['produk.harga', 'DESC', 'Harga tertinggi'],
        'nama'     => ['produk.nama', 'ASC', 'Nama A–Z'],
    ];

    protected $table         = 'produk';
    protected $returnType    = 'array';
    protected $allowedFields = ['kategori_id', 'nama', 'slug', 'deskripsi', 'harga', 'stok', 'foto', 'aktif'];
    protected $useTimestamps = true;
    protected $beforeInsert  = ['buatSlug'];
    protected $beforeUpdate  = ['buatSlug'];

    protected $validationRules = [
        'id'          => 'permit_empty|is_natural_no_zero',
        'kategori_id' => ['label' => 'Kategori', 'rules' => 'required|is_not_unique[kategori.id]'],
        'nama'        => ['label' => 'Nama produk', 'rules' => 'required|min_length[3]|max_length[120]|is_unique[produk.nama,id,{id}]'],
        'deskripsi'   => ['label' => 'Deskripsi', 'rules' => 'required|min_length[10]'],
        'harga'       => ['label' => 'Harga', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[100000000]'],
        'stok'        => ['label' => 'Stok', 'rules' => 'required|is_natural|less_than_equal_to[100000]'],
        'aktif'       => ['label' => 'Status', 'rules' => 'required|in_list[0,1]'],
    ];

    protected function buatSlug(array $data): array
    {
        if (isset($data['data']['nama'])) {
            $data['data']['slug'] = url_title($data['data']['nama'], '-', true);
        }

        return $data;
    }

    public function withKategori(): static
    {
        return $this->select('produk.*, k.nama AS nama_kategori, k.slug AS slug_kategori, k.ikon')
            ->join('kategori k', 'k.id = produk.kategori_id');
    }

    /** Katalog publik: hanya produk aktif, dengan pencarian, filter kategori, urutan, dan pagination. */
    public function katalog(?string $q, ?string $kategoriSlug, string $urut, int $perPage = 8): array
    {
        $this->withKategori()->where('produk.aktif', 1);

        if ($q !== null && $q !== '') {
            $this->groupStart()->like('produk.nama', $q)->orLike('produk.deskripsi', $q)->groupEnd();
        }
        if ($kategoriSlug !== null && $kategoriSlug !== '') {
            $this->where('k.slug', $kategoriSlug);
        }

        [$kolom, $arah] = self::URUTAN[$urut] ?? self::URUTAN['terbaru'];

        return $this->orderBy($kolom, $arah)->orderBy('produk.id', 'DESC')->paginate($perPage);
    }

    public function bySlug(string $slug): ?array
    {
        return $this->withKategori()->where(['produk.slug' => $slug, 'produk.aktif' => 1])->first();
    }

    public function terkait(array $produk, int $limit = 4): array
    {
        return $this->withKategori()
            ->where(['produk.kategori_id' => $produk['kategori_id'], 'produk.aktif' => 1])
            ->where('produk.id !=', $produk['id'])
            ->orderBy('produk.created_at', 'DESC')
            ->findAll($limit);
    }

    /** Produk terlaris berdasarkan jumlah terjual di pesanan yang tidak dibatalkan. */
    public function terlaris(int $limit = 4): array
    {
        return $this->withKategori()
            ->select('COALESCE(SUM(CASE WHEN o.id IS NOT NULL THEN oi.qty ELSE 0 END), 0) AS terjual', false)
            ->join('order_items oi', 'oi.produk_id = produk.id', 'left')
            ->join('orders o', "o.id = oi.order_id AND o.status <> 'dibatalkan'", 'left')
            ->where('produk.aktif', 1)
            ->groupBy('produk.id')
            ->orderBy('terjual', 'DESC')
            ->orderBy('produk.id', 'ASC')
            ->findAll($limit);
    }
}

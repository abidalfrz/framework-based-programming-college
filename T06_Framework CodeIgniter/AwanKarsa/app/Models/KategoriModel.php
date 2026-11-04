<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table          = 'kategori';
    protected $returnType     = 'array';
    protected $allowedFields  = ['nama', 'slug', 'ikon'];
    protected $useTimestamps  = true;
    protected $beforeInsert   = ['buatSlug'];
    protected $beforeUpdate   = ['buatSlug'];

    protected $validationRules = [
        'id'   => 'permit_empty|is_natural_no_zero',
        'nama' => ['label' => 'Nama kategori', 'rules' => 'required|min_length[3]|max_length[60]|is_unique[kategori.nama,id,{id}]'],
        'ikon' => ['label' => 'Ikon', 'rules' => 'permit_empty|alpha_dash|max_length[40]'],
    ];

    protected function buatSlug(array $data): array
    {
        if (isset($data['data']['nama'])) {
            $data['data']['slug'] = url_title($data['data']['nama'], '-', true);
        }

        return $data;
    }

    /** Kategori + jumlah produk aktif. */
    public function denganJumlah(): array
    {
        return $this->select('kategori.*')
            ->select('(SELECT COUNT(*) FROM produk p WHERE p.kategori_id = kategori.id AND p.aktif = 1) AS jumlah', false)
            ->orderBy('nama')
            ->findAll();
    }
}

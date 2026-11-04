<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori_fasilitas';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['kode', 'nama', 'slug', 'icon', 'deskripsi'];
    protected $useTimestamps = true;
    protected $beforeInsert  = ['buatSlug'];
    protected $beforeUpdate  = ['buatSlug'];

    protected $validationRules = [
        'id'        => 'permit_empty|is_natural_no_zero',
        'kode'      => ['label' => 'Kode kategori', 'rules' => 'required|alpha_numeric|max_length[10]|is_unique[kategori_fasilitas.kode,id,{id}]'],
        'nama'      => ['label' => 'Nama kategori', 'rules' => 'required|min_length[3]|max_length[100]|is_unique[kategori_fasilitas.nama,id,{id}]'],
        'icon'      => ['label' => 'Ikon Bootstrap', 'rules' => 'permit_empty|max_length[50]'],
        'deskripsi' => ['label' => 'Deskripsi', 'rules' => 'permit_empty'],
    ];

    protected $validationMessages = [
        'kode' => ['is_unique' => 'Kode kategori sudah digunakan.'],
        'nama' => ['is_unique' => 'Nama kategori sudah terdaftar.'],
    ];

    protected function buatSlug(array $data): array
    {
        if (isset($data['data']['nama'])) {
            helper('url');
            $data['data']['slug'] = url_title($data['data']['nama'], '-', true);
        }

        return $data;
    }

    /**
     * Mengambil kategori beserta hitungan jumlah fasilitas di dalamnya
     */
    public function withJumlahFasilitas(): array
    {
        return $this->select('kategori_fasilitas.*, COUNT(fasilitas.id) AS jumlah_fasilitas')
            ->join('fasilitas', 'fasilitas.kategori_id = kategori_fasilitas.id', 'left')
            ->groupBy('kategori_fasilitas.id')
            ->orderBy('kategori_fasilitas.nama', 'ASC')
            ->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }
}


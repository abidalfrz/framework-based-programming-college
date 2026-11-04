<?php

namespace App\Models;

use CodeIgniter\Model;

class FasilitasModel extends Model
{
    protected $table         = 'fasilitas';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'kode', 'nama', 'slug', 'kategori_id', 'lokasi',
        'kapasitas', 'spesifikasi', 'status', 'penanggung_jawab', 'foto'
    ];
    protected $useTimestamps = true;
    protected $beforeInsert  = ['buatSlug'];
    protected $beforeUpdate  = ['buatSlug'];

    protected $validationRules = [
        'id'               => 'permit_empty|is_natural_no_zero',
        'kode'             => ['label' => 'Kode fasilitas', 'rules' => 'required|min_length[3]|max_length[20]|is_unique[fasilitas.kode,id,{id}]'],
        'nama'             => ['label' => 'Nama fasilitas', 'rules' => 'required|min_length[3]|max_length[120]'],
        'kategori_id'      => ['label' => 'Kategori fasilitas', 'rules' => 'required|is_not_unique[kategori_fasilitas.id]'],
        'lokasi'           => ['label' => 'Lokasi / Gedung', 'rules' => 'required|min_length[3]|max_length[120]'],
        'kapasitas'        => ['label' => 'Kapasitas', 'rules' => 'required|integer|greater_than_equal_to[0]'],
        'status'           => ['label' => 'Status ketersediaan', 'rules' => 'required|in_list[tersedia,pemeliharaan,reservasi]'],
        'penanggung_jawab' => ['label' => 'Penanggung jawab (PIC)', 'rules' => 'required|min_length[3]|max_length[100]'],
        'spesifikasi'      => ['label' => 'Spesifikasi & Fasilitas', 'rules' => 'permit_empty'],
    ];

    protected $validationMessages = [
        'kode'        => ['is_unique' => 'Kode fasilitas ini sudah digunakan.'],
        'kategori_id' => ['is_not_unique' => 'Kategori yang dipilih tidak valid.'],
    ];

    protected function buatSlug(array $data): array
    {
        if (isset($data['data']['nama'])) {
            helper('url');
            $data['data']['slug'] = url_title($data['data']['nama'], '-', true);
        }

        return $data;
    }

    public function withKategori(): self
    {
        return $this->select('fasilitas.*, kategori_fasilitas.nama AS nama_kategori, kategori_fasilitas.kode AS kode_kategori, kategori_fasilitas.slug AS slug_kategori, kategori_fasilitas.icon AS icon_kategori')
            ->join('kategori_fasilitas', 'kategori_fasilitas.id = fasilitas.kategori_id');
    }

    /**
     * Pencarian multifaktor & filter dengan paginasi
     */
    public function cari(?string $keyword, ?int $kategoriId, ?string $status = null, int $perPage = 9): array
    {
        $builder = $this->withKategori();

        if ($keyword !== null && $keyword !== '') {
            $builder->groupStart()
                ->like('fasilitas.nama', $keyword)
                ->orLike('fasilitas.kode', $keyword)
                ->orLike('fasilitas.lokasi', $keyword)
                ->orLike('fasilitas.spesifikasi', $keyword)
                ->groupEnd();
        }

        if ($kategoriId !== null && $kategoriId > 0) {
            $builder->where('fasilitas.kategori_id', $kategoriId);
        }

        if ($status !== null && in_array($status, ['tersedia', 'pemeliharaan', 'reservasi'], true)) {
            $builder->where('fasilitas.status', $status);
        }

        return $builder->orderBy('fasilitas.created_at', 'DESC')->paginate($perPage);
    }

    public function detailBySlug(string $slug): ?array
    {
        return $this->withKategori()->where('fasilitas.slug', $slug)->first();
    }

    public function detailById(int $id): ?array
    {
        return $this->withKategori()->where('fasilitas.id', $id)->first();
    }

    /**
     * Mengambil ringkasan statistik untuk dashboard & beranda
     */
    public function statistik(): array
    {
        $totalFasilitas   = $this->countAllResults(false);
        $totalTersedia    = $this->where('status', 'tersedia')->countAllResults(false);
        $totalReservasi   = $this->where('status', 'reservasi')->countAllResults(false);
        $totalMaintenance = $this->where('status', 'pemeliharaan')->countAllResults(false);
        
        $kapasitas = $this->selectSum('kapasitas')->first()['kapasitas'] ?? 0;
        $totalKategori = (new KategoriModel())->countAllResults();

        return [
            'total_fasilitas'   => (int) $totalFasilitas,
            'tersedia'          => (int) $totalTersedia,
            'reservasi'         => (int) $totalReservasi,
            'pemeliharaan'      => (int) $totalMaintenance,
            'total_kapasitas'   => (int) $kapasitas,
            'total_kategori'    => (int) $totalKategori,
        ];
    }
}


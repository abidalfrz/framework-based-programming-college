<?php

namespace App\Models;

use CodeIgniter\Model;

class MatakuliahModel extends Model
{
    protected $table         = 'matakuliah';
    protected $returnType    = 'array';
    protected $allowedFields = ['kode', 'nama', 'sks', 'semester', 'dosen_id', 'kuota'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'       => 'permit_empty|is_natural_no_zero',
        'kode'     => ['label' => 'Kode', 'rules' => 'required|alpha_numeric|max_length[10]|is_unique[matakuliah.kode,id,{id}]'],
        'nama'     => ['label' => 'Nama mata kuliah', 'rules' => 'required|min_length[3]|max_length[100]'],
        'sks'      => ['label' => 'SKS', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[6]'],
        'semester' => ['label' => 'Semester', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[8]'],
        'dosen_id' => ['label' => 'Dosen pengampu', 'rules' => 'required|is_not_unique[dosen.id]'],
        'kuota'    => ['label' => 'Kuota', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[200]'],
    ];

    public function withDosen(): static
    {
        return $this->select('matakuliah.*, u.nama AS nama_dosen, d.nidn')
            ->join('dosen d', 'd.id = matakuliah.dosen_id')
            ->join('users u', 'u.id = d.user_id');
    }

    /** Tambahkan kolom `peserta` = jumlah mahasiswa di KRS periode tertentu. */
    private function withPeserta(string $tahunAjaran, string $semester): static
    {
        $ta  = $this->db->escape($tahunAjaran);
        $sem = $this->db->escape($semester);

        return $this->select(
            "(SELECT COUNT(*) FROM krs k WHERE k.matakuliah_id = matakuliah.id AND k.tahun_ajaran = {$ta} AND k.semester = {$sem}) AS peserta",
            false,
        );
    }

    /** Mata kuliah yang dibuka pada periode ini (ganjil: semester 1,3,5,7). */
    public function ditawarkan(string $tahunAjaran, string $semester): array
    {
        $paritas = $semester === 'ganjil' ? 1 : 0;

        return $this->withDosen()
            ->withPeserta($tahunAjaran, $semester)
            ->where("MOD(matakuliah.semester, 2) = {$paritas}")
            ->orderBy('matakuliah.semester')
            ->orderBy('matakuliah.kode')
            ->findAll();
    }

    /** Kelas yang diampu seorang dosen pada periode ini. */
    public function kelasDosen(int $dosenId, string $tahunAjaran, string $semester): array
    {
        return $this->withDosen()
            ->withPeserta($tahunAjaran, $semester)
            ->where('matakuliah.dosen_id', $dosenId)
            ->orderBy('matakuliah.kode')
            ->findAll();
    }

    public function semua(): array
    {
        return $this->withDosen()->orderBy('matakuliah.semester')->orderBy('matakuliah.kode')->findAll();
    }
}

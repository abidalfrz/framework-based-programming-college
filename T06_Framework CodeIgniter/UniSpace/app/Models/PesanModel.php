<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table         = 'pesan_fasilitas';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'email', 'no_hp', 'fasilitas_id', 'subjek', 'isi', 'status'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'           => 'permit_empty|is_natural_no_zero',
        'nama'         => ['label' => 'Nama lengkap', 'rules' => 'required|min_length[3]|max_length[100]'],
        'email'        => ['label' => 'Alamat email', 'rules' => 'required|valid_email|max_length[100]'],
        'no_hp'        => ['label' => 'Nomor HP/WhatsApp', 'rules' => 'permit_empty|min_length[8]|max_length[20]'],
        'fasilitas_id' => ['label' => 'Fasilitas terkait', 'rules' => 'permit_empty|is_not_unique[fasilitas.id]'],
        'subjek'       => ['label' => 'Subjek pertanyaan/aduan', 'rules' => 'required|min_length[5]|max_length[150]'],
        'isi'          => ['label' => 'Isi pesan', 'rules' => 'required|min_length[10]'],
        'status'       => ['label' => 'Status pesan', 'rules' => 'permit_empty|in_list[baru,dibaca,selesai]'],
    ];

    public function withFasilitas(): self
    {
        return $this->select('pesan_fasilitas.*, fasilitas.nama AS nama_fasilitas, fasilitas.kode AS kode_fasilitas')
            ->join('fasilitas', 'fasilitas.id = pesan_fasilitas.fasilitas_id', 'left');
    }
}


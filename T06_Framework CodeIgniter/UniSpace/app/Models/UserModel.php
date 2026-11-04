<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'username', 'password_hash', 'role'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'       => 'permit_empty|is_natural_no_zero',
        'nama'     => ['label' => 'Nama lengkap', 'rules' => 'required|min_length[3]|max_length[100]'],
        'username' => ['label' => 'Username', 'rules' => 'required|alpha_dash|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]'],
        'role'     => ['label' => 'Peran (Role)', 'rules' => 'required|in_list[admin,staff]'],
    ];

    protected $validationMessages = [
        'username' => ['is_unique' => 'Username sudah digunakan oleh akun lain.'],
    ];

    /**
     * Memverifikasi login user berdasarkan username & plain password
     */
    public function verifikasi(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}


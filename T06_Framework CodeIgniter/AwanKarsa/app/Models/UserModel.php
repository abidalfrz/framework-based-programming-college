<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'email', 'password_hash', 'role', 'telepon', 'alamat'];
    protected $useTimestamps = true;

    public function verifikasi(string $email, string $password): ?array
    {
        $user = $this->where('email', strtolower($email))->first();

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}

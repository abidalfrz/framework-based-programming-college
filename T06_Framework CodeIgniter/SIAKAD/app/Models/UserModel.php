<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'password_hash', 'nama', 'role'];
    protected $useTimestamps = true;

    /** Mengembalikan data user jika username & password cocok, selain itu null. */
    public function verifikasi(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}

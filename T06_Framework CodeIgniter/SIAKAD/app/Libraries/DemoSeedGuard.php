<?php

namespace App\Libraries;

use Config\Database;
use RuntimeException;

/** Prevent accidental demo inserts into an existing or unrelated database. */
final class DemoSeedGuard
{
    public const DATABASE = 'siakad';
    public const TABLES = ['users', 'dosen', 'mahasiswa', 'matakuliah', 'krs'];

    public static function connection(): \CodeIgniter\Database\BaseConnection
    {
        $config = config('Database')->default;
        if ($config['DBDriver'] !== 'MySQLi'
            || ! in_array($config['hostname'], ['localhost', '127.0.0.1'], true)
            || $config['DBPrefix'] !== '') {
            throw new RuntimeException('Setup ditolak: target harus MySQL lokal tanpa prefix.');
        }
        $db = Database::connect('default');
        $actual = $db->query('SELECT DATABASE() AS db')->getRowArray()['db'];
        if ($actual !== $config['database']) {
            throw new RuntimeException('Setup ditolak: database aktif tidak cocok dengan konfigurasi.');
        }

        return $db;
    }

    public static function requireEmpty(): \CodeIgniter\Database\BaseConnection
    {
        $db = self::connection();
        foreach (self::TABLES as $table) {
            if (! $db->tableExists($table) || $db->table($table)->countAllResults() !== 0) {
                throw new RuntimeException('Demo seed ditolak: semua tabel aplikasi harus sudah dimigrasi dan kosong. Data lama dipertahankan.');
            }
        }

        return $db;
    }
}

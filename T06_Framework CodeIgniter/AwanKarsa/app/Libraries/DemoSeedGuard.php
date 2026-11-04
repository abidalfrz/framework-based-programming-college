<?php

namespace App\Libraries;

use Config\Database;
use RuntimeException;

/** Prevent accidental demo inserts into an existing or unrelated database. */
final class DemoSeedGuard
{
    public const DATABASE = 'awankarsa';
    public const TABLES = ['users', 'kategori', 'produk', 'orders', 'order_items'];

    public static function connection(): \CodeIgniter\Database\BaseConnection
    {
        $config = config('Database')->default;
        if ($config['database'] !== self::DATABASE || $config['DBDriver'] !== 'MySQLi'
            || ! in_array($config['hostname'], ['localhost', '127.0.0.1'], true)
            || $config['DBPrefix'] !== '') {
            throw new RuntimeException('Setup ditolak: target harus MySQL lokal ' . self::DATABASE . ' tanpa prefix.');
        }
        $db = Database::connect('default');
        $actual = $db->query('SELECT DATABASE() AS db')->getRowArray()['db'];
        if ($actual !== self::DATABASE) {
            throw new RuntimeException('Setup ditolak: database aktif tidak cocok.');
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

<?php

namespace App\Commands;

use App\Libraries\DemoSeedGuard;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Throwable;

/** Additive setup only; never creates accounts, drops tables or resets data. */
final class ProjectSetup extends BaseCommand
{
    protected $group = 'Project';
    protected $name = 'project:setup';
    protected $description = 'Verifikasi DB proyek lalu jalankan migration tambahan; --seed hanya untuk tabel kosong.';
    protected $options = ['--seed' => 'Isi data demo hanya bila semua tabel aplikasi kosong.'];

    public function run(array $params)
    {
        try {
            $db = DemoSeedGuard::connection();
            // A populated unversioned schema must be reviewed before adding migrations.
            if (! $db->tableExists('migrations')) {
                foreach (DemoSeedGuard::TABLES as $table) {
                    if ($db->tableExists($table)) {
                        CLI::error('Tabel aplikasi sudah ada tanpa riwayat migration. Tinjau manual; tidak ada perubahan.');
                        return EXIT_ERROR;
                    }
                }
            }
            CLI::write('Target terverifikasi: ' . DemoSeedGuard::DATABASE);
            if (! service('migrations')->latest()) {
                CLI::error('Migration tidak selesai. Periksa log aplikasi.');
                return EXIT_ERROR;
            }
            if (CLI::getOption('seed')) {
                \Config\Database::seeder('default')->call(\App\Database\Seeds\DatabaseSeeder::class);
            }
            CLI::write('Setup selesai; data lama tidak direset.', 'green');
            return EXIT_SUCCESS;
        } catch (Throwable $e) {
            // Do not print exception details: database errors may contain connection credentials.
            CLI::error('Setup tertahan. Periksa kredensial lokal, izin DB, dan pastikan tabel seed kosong. Tidak ada reset data.');
            return EXIT_ERROR;
        }
    }
}

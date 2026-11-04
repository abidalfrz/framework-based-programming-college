<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRentalPeriod extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('orders', [
            'durasi_bulan' => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'mulai_sewa' => ['type' => 'DATETIME', 'null' => true],
            'akhir_sewa' => ['type' => 'DATETIME', 'null' => true],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', ['durasi_bulan', 'mulai_sewa', 'akhir_sewa']);
    }
}

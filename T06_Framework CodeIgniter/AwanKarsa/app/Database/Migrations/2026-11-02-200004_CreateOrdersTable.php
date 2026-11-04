<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode'          => ['type' => 'VARCHAR', 'constraint' => 20],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'        => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu_pembayaran', 'menunggu_verifikasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan'],
                'default'    => 'menunggu_pembayaran',
            ],
            'subtotal'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'ongkir'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_penerima' => ['type' => 'VARCHAR', 'constraint' => 100],
            'telepon'       => ['type' => 'VARCHAR', 'constraint' => 20],
            'alamat'        => ['type' => 'TEXT'],
            'catatan'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bukti_bayar'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'resi'          => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'catatan_admin' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('kode');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('orders');
    }

    public function down(): void
    {
        $this->forge->dropTable('orders');
    }
}

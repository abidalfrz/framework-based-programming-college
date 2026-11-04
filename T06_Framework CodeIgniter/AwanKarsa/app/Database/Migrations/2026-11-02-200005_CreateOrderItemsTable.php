<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'produk_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            // Nama & harga disalin saat checkout agar riwayat tidak berubah bila produk diedit.
            'nama_produk' => ['type' => 'VARCHAR', 'constraint' => 120],
            'harga'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'qty'         => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true],
            'subtotal'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('order_items');
    }

    public function down(): void
    {
        $this->forge->dropTable('order_items');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdukTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 140],
            'deskripsi'   => ['type' => 'TEXT'],
            'harga'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'comment' => 'Rupiah'],
            'stok'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'foto'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'aktif'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('produk');
    }

    public function down(): void
    {
        $this->forge->dropTable('produk');
    }
}

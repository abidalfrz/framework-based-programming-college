<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriFasilitasTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 120],
            'icon'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'bi-building'],
            'deskripsi'  => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('kategori_fasilitas', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('kategori_fasilitas', true);
    }
}


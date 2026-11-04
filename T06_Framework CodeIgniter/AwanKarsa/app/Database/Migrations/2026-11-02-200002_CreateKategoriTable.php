<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 60],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 80],
            'ikon'       => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'bag'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('kategori');
    }

    public function down(): void
    {
        $this->forge->dropTable('kategori');
    }
}

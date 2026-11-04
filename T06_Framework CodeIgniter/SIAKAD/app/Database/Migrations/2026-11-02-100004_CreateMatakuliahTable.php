<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMatakuliahTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'sks'        => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'semester'   => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'comment' => 'Semester kurikulum 1-8'],
            'dosen_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'kuota'      => ['type' => 'SMALLINT', 'constraint' => 4, 'unsigned' => true, 'default' => 40],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('kode');
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('matakuliah');
    }

    public function down(): void
    {
        $this->forge->dropTable('matakuliah');
    }
}

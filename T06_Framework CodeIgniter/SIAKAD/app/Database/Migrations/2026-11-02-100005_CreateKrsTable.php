<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKrsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'mahasiswa_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'matakuliah_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tahun_ajaran'  => ['type' => 'VARCHAR', 'constraint' => 9],
            'semester'      => ['type' => 'ENUM', 'constraint' => ['ganjil', 'genap']],
            'nilai_angka'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'nilai_huruf'   => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        // Satu mata kuliah hanya boleh diambil sekali per periode.
        $this->forge->addUniqueKey(['mahasiswa_id', 'matakuliah_id', 'tahun_ajaran', 'semester'], 'uniq_krs_periode');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('matakuliah_id', 'matakuliah', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('krs');
    }

    public function down(): void
    {
        $this->forge->dropTable('krs');
    }
}

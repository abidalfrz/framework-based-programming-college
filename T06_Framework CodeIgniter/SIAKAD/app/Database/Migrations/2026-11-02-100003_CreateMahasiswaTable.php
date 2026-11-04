<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswaTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nim'           => ['type' => 'VARCHAR', 'constraint' => 10],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'angkatan'      => ['type' => 'SMALLINT', 'constraint' => 4, 'unsigned' => true],
            'dosen_wali_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nim');
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // Dosen yang masih menjadi wali tidak boleh dihapus.
        $this->forge->addForeignKey('dosen_wali_id', 'dosen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('mahasiswa');
    }

    public function down(): void
    {
        $this->forge->dropTable('mahasiswa');
    }
}

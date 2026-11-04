<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => 30],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'role'          => ['type' => 'ENUM', 'constraint' => ['admin', 'dosen', 'mahasiswa'], 'default' => 'mahasiswa'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users');
    }

    public function down(): void
    {
        $this->forge->dropTable('users');
    }
}

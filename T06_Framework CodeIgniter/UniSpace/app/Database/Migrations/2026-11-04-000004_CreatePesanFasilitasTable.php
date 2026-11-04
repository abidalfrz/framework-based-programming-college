<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesanFasilitasTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'no_hp'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'fasilitas_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'subjek'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'isi'          => ['type' => 'TEXT'],
            'status'       => ['type' => 'ENUM', 'constraint' => ['baru', 'dibaca', 'selesai'], 'default' => 'baru'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('fasilitas_id');
        $this->forge->addForeignKey('fasilitas_id', 'fasilitas', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pesan_fasilitas', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('pesan_fasilitas', true);
    }
}


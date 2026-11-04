<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFasilitasTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode'             => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'             => ['type' => 'VARCHAR', 'constraint' => 120],
            'slug'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'kategori_id'      => ['type' => 'INT', 'unsigned' => true],
            'lokasi'           => ['type' => 'VARCHAR', 'constraint' => 120],
            'kapasitas'        => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'spesifikasi'      => ['type' => 'TEXT', 'null' => true],
            'status'           => ['type' => 'ENUM', 'constraint' => ['tersedia', 'pemeliharaan', 'reservasi'], 'default' => 'tersedia'],
            'penanggung_jawab' => ['type' => 'VARCHAR', 'constraint' => 100],
            'foto'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode');
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('kategori_id');
        $this->forge->addForeignKey('kategori_id', 'kategori_fasilitas', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('fasilitas', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('fasilitas', true);
    }
}


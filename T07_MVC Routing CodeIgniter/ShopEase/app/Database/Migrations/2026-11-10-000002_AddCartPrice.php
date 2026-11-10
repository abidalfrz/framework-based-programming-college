<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCartPrice extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('cart_items', ['price_at_add' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true]]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('cart_items', 'price_at_add');
    }
}

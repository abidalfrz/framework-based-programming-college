<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShopSchema extends Migration
{
    public function up(): void
    {
        $t = fn (string $name): string => '`' . $this->db->prefixTable($name) . '`';
        $tables = [
            'users' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, role VARCHAR(20) NOT NULL DEFAULT 'customer', phone VARCHAR(30) NULL, is_active TINYINT NOT NULL DEFAULT 1, created_at DATETIME NULL, updated_at DATETIME NULL",
            'categories' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, slug VARCHAR(120) NOT NULL UNIQUE, description TEXT NULL, is_active TINYINT NOT NULL DEFAULT 1, created_at DATETIME NULL, updated_at DATETIME NULL",
            'products' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, category_id INT UNSIGNED NOT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(180) NOT NULL UNIQUE, description TEXT NULL, price DECIMAL(12,2) NOT NULL, stock INT UNSIGNED NOT NULL DEFAULT 0, image VARCHAR(255) NULL, status VARCHAR(20) NOT NULL DEFAULT 'active', created_at DATETIME NULL, updated_at DATETIME NULL, FOREIGN KEY (category_id) REFERENCES {$t('categories')}(id)",
            'carts' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL UNIQUE, created_at DATETIME NULL, updated_at DATETIME NULL, FOREIGN KEY (user_id) REFERENCES {$t('users')}(id)",
            'cart_items' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, cart_id INT UNSIGNED NOT NULL, product_id INT UNSIGNED NOT NULL, quantity INT UNSIGNED NOT NULL, created_at DATETIME NULL, updated_at DATETIME NULL, UNIQUE KEY cart_product (cart_id, product_id), FOREIGN KEY (cart_id) REFERENCES {$t('carts')}(id), FOREIGN KEY (product_id) REFERENCES {$t('products')}(id)",
            'addresses' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, recipient_name VARCHAR(100) NOT NULL, phone VARCHAR(30) NOT NULL, address TEXT NOT NULL, city VARCHAR(100) NOT NULL, province VARCHAR(100) NOT NULL, postal_code VARCHAR(20) NOT NULL, is_default TINYINT NOT NULL DEFAULT 0, created_at DATETIME NULL, updated_at DATETIME NULL, FOREIGN KEY (user_id) REFERENCES {$t('users')}(id)",
            'orders' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, order_number VARCHAR(50) NOT NULL UNIQUE, checkout_token VARCHAR(100) NOT NULL UNIQUE, recipient_name VARCHAR(100) NOT NULL, phone VARCHAR(30) NOT NULL, address TEXT NOT NULL, city VARCHAR(100) NOT NULL, province VARCHAR(100) NOT NULL, postal_code VARCHAR(20) NOT NULL, subtotal DECIMAL(12,2) NOT NULL, shipping_cost DECIMAL(12,2) NOT NULL, total DECIMAL(12,2) NOT NULL, order_status VARCHAR(30) NOT NULL, created_at DATETIME NULL, updated_at DATETIME NULL, FOREIGN KEY (user_id) REFERENCES {$t('users')}(id), INDEX status_index (order_status)",
            'order_items' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, order_id INT UNSIGNED NOT NULL, product_id INT UNSIGNED NOT NULL, product_name VARCHAR(150) NOT NULL, price DECIMAL(12,2) NOT NULL, quantity INT UNSIGNED NOT NULL, subtotal DECIMAL(12,2) NOT NULL, FOREIGN KEY (order_id) REFERENCES {$t('orders')}(id), FOREIGN KEY (product_id) REFERENCES {$t('products')}(id)",
            'payments' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, order_id INT UNSIGNED NOT NULL UNIQUE, amount DECIMAL(12,2) NOT NULL, payment_status VARCHAR(30) NOT NULL DEFAULT 'unpaid', proof_path VARCHAR(255) NULL, rejection_reason TEXT NULL, verified_by INT UNSIGNED NULL, verified_at DATETIME NULL, created_at DATETIME NULL, updated_at DATETIME NULL, FOREIGN KEY (order_id) REFERENCES {$t('orders')}(id), FOREIGN KEY (verified_by) REFERENCES {$t('users')}(id)",
            'shipments' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, order_id INT UNSIGNED NOT NULL UNIQUE, courier VARCHAR(100) NOT NULL, tracking_number VARCHAR(100) NOT NULL, shipped_at DATETIME NULL, delivered_at DATETIME NULL, FOREIGN KEY (order_id) REFERENCES {$t('orders')}(id)",
            'order_status_events' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, order_id INT UNSIGNED NOT NULL, actor_id INT UNSIGNED NOT NULL, old_status VARCHAR(30) NULL, new_status VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, FOREIGN KEY (order_id) REFERENCES {$t('orders')}(id), FOREIGN KEY (actor_id) REFERENCES {$t('users')}(id)",
            'settings' => "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0, payment_instructions TEXT NULL, checkout_ready TINYINT NOT NULL DEFAULT 0, updated_at DATETIME NULL",
        ];
        foreach ($tables as $name => $columns) {
            $this->db->query("CREATE TABLE {$t($name)} ({$columns}) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }

    public function down(): void
    {
        foreach (['settings', 'order_status_events', 'shipments', 'payments', 'order_items', 'orders', 'addresses', 'cart_items', 'carts', 'products', 'categories', 'users'] as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}

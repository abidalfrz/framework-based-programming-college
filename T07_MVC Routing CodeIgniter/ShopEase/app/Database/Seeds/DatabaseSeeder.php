<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['users', 'categories', 'products', 'settings'] as $table) {
            if ($this->db->table($table)->countAllResults() !== 0) {
                throw new RuntimeException('Demo seeding requires empty ShopEase tables.');
            }
        }

        $this->db->transStart();
        $password = password_hash('demo1234', PASSWORD_DEFAULT);
        foreach ([['Demo Admin', 'admin@shopease.test', 'admin'], ['Demo Operator', 'operator@shopease.test', 'operator'], ['Demo Customer', 'customer@shopease.test', 'customer'], ['Second Customer', 'customer2@shopease.test', 'customer']] as [$name, $email, $role]) {
            $this->db->table('users')->insert(['name' => $name, 'email' => $email, 'password_hash' => $password, 'role' => $role]);
        }
        foreach (CatalogProducts::all() as $category => $products) {
            $this->db->table('categories')->insert(['name' => $category, 'slug' => strtolower($category)]);
            $id = $this->db->insertID();
            foreach ($products as $index => [$name, $image]) {
                $i = $index + 1;
                $this->db->table('products')->insert(['category_id' => $id, 'name' => $name, 'slug' => strtolower($category) . "-demo-{$i}", 'description' => $name . ' is part of the ShopEase ' . strtolower($category) . ' collection.', 'image' => $image, 'price' => 100000 + $i * 25000, 'stock' => 10, 'status' => 'active']);
            }
        }
        $this->db->table('settings')->insert(['shipping_cost' => 0, 'payment_instructions' => null, 'checkout_ready' => 0]);
        $this->db->transComplete();
        if (! $this->db->transStatus()) {
            throw new RuntimeException('Unable to seed demo data.');
        }
    }
}

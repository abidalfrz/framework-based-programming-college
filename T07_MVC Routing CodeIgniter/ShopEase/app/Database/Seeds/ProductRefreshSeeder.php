<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

/** Update only untouched products from the original sample seeder. */
class ProductRefreshSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->transStart();
        foreach (CatalogProducts::all() as $category => $products) {
            foreach ($products as $index => [$name, $image]) {
                $number = $index + 1;
                $this->db->table('products')
                    ->where('name', "Demo {$category} {$number}")
                    ->where('slug', strtolower($category) . "-demo-{$number}")
                    ->where('description', 'Demonstration product.')
                    ->where('image', null)
                    ->update(['name' => $name, 'image' => $image, 'description' => 'Example product listing for the ShopEase learning project.']);
                if ($image !== null) {
                    $this->db->table('products')
                        ->where('name', $name)
                        ->where('slug', strtolower($category) . "-demo-{$number}")
                        ->where('description', 'Example product listing for the ShopEase learning project.')
                        ->where('image', null)
                        ->update(['image' => $image]);
                }
                $this->db->table('products')
                    ->where('name', $name)
                    ->where('slug', strtolower($category) . "-demo-{$number}")
                    ->where('description', 'Example product listing for the ShopEase learning project.')
                    ->update(['description' => $name . ' is part of the ShopEase ' . strtolower($category) . ' collection.']);
            }
        }
        $this->db->transComplete();
        if (! $this->db->transStatus()) {
            throw new RuntimeException('Unable to update sample products.');
        }
    }
}

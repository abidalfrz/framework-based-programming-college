<?php

namespace App\Services;

use Config\Database;
use DomainException;
use Throwable;

class CartService
{
    public function add(int $userId, int $productId, int $quantity): void
    {
        if ($productId < 1 || $quantity < 1) { throw new DomainException('Enter a valid quantity.'); }
        $this->withLockedCart($userId, true, function ($db, $cartId) use ($productId, $quantity): void {
            $product = $db->table('products')->select('products.*')->join('categories', 'categories.id = products.category_id')->where('products.id', $productId)->where('products.status', 'active')->where('categories.is_active', 1)->get()->getRowArray();
            if (! $product) { throw new DomainException('This product is not available.'); }
            $item = $db->table('cart_items')->where('cart_id', $cartId)->where('product_id', $productId)->get()->getRowArray();
            $total = $quantity + (int) ($item['quantity'] ?? 0);
            if ($total > (int) $product['stock']) { throw new DomainException('The requested quantity exceeds available stock.'); }
            $data = ['quantity' => $total, 'price_at_add' => $product['price'], 'updated_at' => date('Y-m-d H:i:s')];
            if ($item) { $db->table('cart_items')->where('id', $item['id'])->where('cart_id', $cartId)->update($data); }
            else { $db->table('cart_items')->insert($data + ['cart_id' => $cartId, 'product_id' => $productId, 'created_at' => date('Y-m-d H:i:s')]); }
        });
    }

    public function update(int $userId, int $itemId, int $quantity): void
    {
        if ($quantity < 1) { throw new DomainException('Enter a valid quantity.'); }
        $this->withLockedCart($userId, false, function ($db, $cartId) use ($itemId, $quantity): void {
            $item = $db->table('cart_items')->where('id', $itemId)->where('cart_id', $cartId)->get()->getRowArray();
            $product = $item ? $db->table('products')->select('products.*')->join('categories', 'categories.id = products.category_id')->where('products.id', $item['product_id'])->where('products.status', 'active')->where('categories.is_active', 1)->get()->getRowArray() : null;
            if (! $product || $quantity > (int) $product['stock']) { throw new DomainException('Please enter a quantity within available stock.'); }
            $db->table('cart_items')->where('id', $itemId)->where('cart_id', $cartId)->update(['quantity' => $quantity, 'price_at_add' => $product['price'], 'updated_at' => date('Y-m-d H:i:s')]);
        });
    }

    public function remove(int $userId, int $itemId): void
    {
        $this->withLockedCart($userId, false, function ($db, $cartId) use ($itemId): void {
            $db->table('cart_items')->where('id', $itemId)->where('cart_id', $cartId)->delete();
        });
    }

    private function withLockedCart(int $userId, bool $create, callable $action): void
    {
        $db = Database::connect();
        $db->transBegin();
        try {
            $users = $db->protectIdentifiers($db->prefixTable('users'));
            $user = $db->query("SELECT id FROM {$users} WHERE id = ? FOR UPDATE", [$userId])->getRowArray();
            if (! $user) { throw new DomainException('Account not found.'); }
            $carts = $db->protectIdentifiers($db->prefixTable('carts'));
            $cart = $db->query("SELECT id FROM {$carts} WHERE user_id = ? FOR UPDATE", [$userId])->getRowArray();
            if (! $cart && $create) {
                $db->table('carts')->insert(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                $cart = ['id' => $db->insertID()];
            }
            if (! $cart) { throw new DomainException('Cart not found.'); }
            $action($db, (int) $cart['id']);
            if (! $db->transStatus()) { throw new DomainException('Cart could not be updated.'); }
            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}

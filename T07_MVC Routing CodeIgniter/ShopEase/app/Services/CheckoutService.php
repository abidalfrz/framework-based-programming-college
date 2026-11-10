<?php

namespace App\Services;

use Config\Database;
use DomainException;
use Throwable;

class CheckoutService
{
    public function placeOrder(int $userId, int $addressId, string $checkoutToken, ?int $expectedTotalCents = null): int
    {
        if ($userId < 1 || $addressId < 1 || $checkoutToken === '' || strlen($checkoutToken) > 100) {
            throw new DomainException('Invalid checkout details.');
        }
        $db = Database::connect();
        $existing = $db->table('orders')->where('checkout_token', $checkoutToken)->get()->getRowArray();
        if ($existing) {
            if ((int) $existing['user_id'] !== $userId) {
                throw new DomainException('Invalid checkout token.');
            }
            return (int) $existing['id'];
        }
        $settings = $db->table('settings')->get()->getRowArray();
        if (! $settings || ! $settings['checkout_ready'] || trim((string) $settings['payment_instructions']) === '') {
            throw new DomainException('Checkout is not available yet.');
        }
        $address = $db->table('addresses')->where('id', $addressId)->where('user_id', $userId)->get()->getRowArray();
        if (! $address) {
            throw new DomainException('Please select a valid address.');
        }
        $db->transBegin();
        try {
            $cartTable = $db->protectIdentifiers($db->prefixTable('carts'));
            $cart = $db->query("SELECT * FROM {$cartTable} WHERE user_id = ? FOR UPDATE", [$userId])->getRowArray();
            $existing = $db->table('orders')->where('checkout_token', $checkoutToken)->get()->getRowArray();
            if ($existing && (int) $existing['user_id'] === $userId) {
                $db->transCommit();
                return (int) $existing['id'];
            }
            if (! $cart || $existing) {
                throw new DomainException('Your cart is empty or the checkout token is invalid.');
            }
            $items = $db->table('cart_items')->where('cart_id', $cart['id'])->orderBy('product_id', 'ASC')->get()->getResultArray();
            if (! $items) {
                throw new DomainException('Your cart is empty.');
            }
            $productTable = $db->protectIdentifiers($db->prefixTable('products'));
            $subtotal = 0;
            $lines = [];
            foreach ($items as $item) {
                $product = $db->query("SELECT * FROM {$productTable} WHERE id = ? FOR UPDATE", [$item['product_id']])->getRowArray();
                $quantity = (int) $item['quantity'];
                $category = $product ? $db->table('categories')->where('id', $product['category_id'])->where('is_active', 1)->get()->getRowArray() : null;
                if (! $category || ! $product || $product['status'] !== 'active' || $quantity < 1 || $quantity > (int) $product['stock']) {
                    throw new DomainException('A cart item is unavailable or has insufficient stock.');
                }
                if ($item['price_at_add'] === null || $this->cents((string) $item['price_at_add']) !== $this->cents((string) $product['price'])) {
                    throw new DomainException('A product price has changed. Please review your cart.');
                }
                $price = $this->cents($product['price']);
                if ($price > 0 && $quantity > intdiv(999999999999, $price)) {
                    throw new DomainException('Order total is too large.');
                }
                $lineTotal = $price * $quantity;
                if ($subtotal > 999999999999 - $lineTotal) {
                    throw new DomainException('Order total is too large.');
                }
                $subtotal += $lineTotal;
                $lines[] = [$product, $quantity, $lineTotal];
            }
            $shipping = $this->cents($settings['shipping_cost']);
            if ($subtotal + $shipping > 999999999999) {
                throw new DomainException('Order total is too large.');
            }
            if ($expectedTotalCents !== null && $subtotal + $shipping !== $expectedTotalCents) {
                throw new DomainException('The order total has changed. Please review your cart.');
            }
            $now = date('Y-m-d H:i:s');
            $db->table('orders')->insert(['user_id' => $userId, 'order_number' => 'SE-' . strtoupper(bin2hex(random_bytes(8))), 'checkout_token' => $checkoutToken, 'recipient_name' => $address['recipient_name'], 'phone' => $address['phone'], 'address' => $address['address'], 'city' => $address['city'], 'province' => $address['province'], 'postal_code' => $address['postal_code'], 'subtotal' => $this->money($subtotal), 'shipping_cost' => $this->money($shipping), 'total' => $this->money($subtotal + $shipping), 'order_status' => 'pending_payment', 'created_at' => $now, 'updated_at' => $now]);
            $orderId = (int) $db->insertID();
            foreach ($lines as [$product, $quantity, $lineTotal]) {
                $db->table('order_items')->insert(['order_id' => $orderId, 'product_id' => $product['id'], 'product_name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity, 'subtotal' => $this->money($lineTotal)]);
                $db->table('products')->where('id', $product['id'])->set('stock', 'stock - ' . $quantity, false)->update();
            }
            $db->table('payments')->insert(['order_id' => $orderId, 'amount' => $this->money($subtotal + $shipping), 'payment_status' => 'unpaid', 'created_at' => $now, 'updated_at' => $now]);
            $db->table('order_status_events')->insert(['order_id' => $orderId, 'actor_id' => $userId, 'new_status' => 'pending_payment', 'created_at' => $now]);
            $db->table('cart_items')->where('cart_id', $cart['id'])->delete();
            if (! $db->transStatus()) {
                throw new DomainException('Your order could not be placed.');
            }
            $db->transCommit();
            return $orderId;
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    private function cents(string $amount): int
    {
        if (! preg_match('/^(\d+)\.(\d{2})$/', $amount, $parts)) {
            throw new DomainException('Invalid monetary amount.');
        }
        return (int) $parts[1] * 100 + (int) $parts[2];
    }

    private function money(int $cents): string
    {
        return intdiv($cents, 100) . '.' . str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
}

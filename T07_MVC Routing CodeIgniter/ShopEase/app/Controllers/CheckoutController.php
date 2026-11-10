<?php

namespace App\Controllers;

use App\Models\AddressModel;
use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\SettingModel;
use App\Services\CheckoutService;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

class CheckoutController extends BaseController
{
    public function index(): string
    {
        $cart = (new CartModel())->where('user_id', (int) session('user_id'))->first();
        $items = $cart ? (new CartItemModel())->select('cart_items.*, products.name, products.price, products.stock, products.status, categories.is_active AS category_active')->join('products', 'products.id = cart_items.product_id')->join('categories', 'categories.id = products.category_id')->where('cart_id', $cart['id'])->findAll() : [];
        $settings = (new SettingModel())->first();
        $ready = $settings && $settings['checkout_ready'] && trim((string) $settings['payment_instructions']) !== '';
        $subtotal = 0;
        $itemsAvailable = true;
        $tooLarge = false;
        foreach ($items as $item) {
            $price = money_to_cents((string) $item['price']);
            $quantity = (int) $item['quantity'];
            if (($price > 0 && $quantity > intdiv(999999999999, $price)) || $subtotal > 999999999999 - $price * $quantity) { $tooLarge = true; break; }
            $subtotal += $price * $quantity;
            if (! $item['category_active'] || $item['status'] !== 'active' || $quantity > (int) $item['stock'] || $item['price_at_add'] === null || money_to_cents((string) $item['price_at_add']) !== $price) { $itemsAvailable = false; }
        }
        $shipping = money_to_cents((string) ($settings['shipping_cost'] ?? '0.00'));
        if ($subtotal > 999999999999 - $shipping) { $tooLarge = true; }
        $total = $tooLarge ? 0 : $subtotal + $shipping;
        $token = bin2hex(random_bytes(24));
        session()->set(['checkout_token' => $token, 'checkout_total' => $tooLarge ? null : $total]);
        return view('checkout/index', ['title' => 'Checkout — ShopEase', 'addresses' => (new AddressModel())->where('user_id', (int) session('user_id'))->findAll(), 'items' => $items, 'settings' => $settings, 'ready' => $ready, 'itemsAvailable' => $itemsAvailable, 'tooLarge' => $tooLarge, 'subtotal' => $subtotal, 'total' => $total, 'token' => $token]);
    }

    public function place(): RedirectResponse
    {
        $token = (string) $this->request->getPost('checkout_token');
        if ($token === '' || ! hash_equals((string) session('checkout_token'), $token)) {
            return redirect()->to('/checkout')->with('error', 'Your checkout session has expired. Please try again.');
        }
        try {
            $orderId = (new CheckoutService())->placeOrder((int) session('user_id'), (int) $this->request->getPost('address_id'), $token, is_int(session('checkout_total')) ? session('checkout_total') : -1);
            return redirect()->to('/orders/' . $orderId)->with('success', 'Your order was placed.');
        } catch (DomainException $e) {
            return redirect()->to('/checkout')->with('error', $e->getMessage());
        }
    }
}

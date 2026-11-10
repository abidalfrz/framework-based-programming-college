<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Services\CartService;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

class CartController extends BaseController
{
    public function index(): string
    {
        $cart = (new CartModel())->where('user_id', (int) session('user_id'))->first();
        $items = $cart ? (new CartItemModel())->select('cart_items.*, products.name, products.slug, products.price, products.stock, products.status, categories.is_active AS category_active')->join('products', 'products.id = cart_items.product_id')->join('categories', 'categories.id = products.category_id')->where('cart_id', $cart['id'])->findAll() : [];
        $subtotal = 0;
        foreach ($items as &$item) {
            $price = money_to_cents((string) $item['price']);
            $quantity = (int) $item['quantity'];
            $item['line_total'] = $price > 0 && $quantity > intdiv(999999999999, $price) ? null : $price * $quantity;
            if ($item['line_total'] === null || $subtotal > 999999999999 - $item['line_total']) { $subtotal = null; break; }
            $subtotal += $item['line_total'];
        }
        unset($item);
        return view('cart/index', ['title' => 'Your cart — ShopEase', 'items' => $items, 'subtotal' => $subtotal]);
    }

    public function add(): RedirectResponse
    {
        $productId = (string) $this->request->getPost('product_id');
        $quantity = (string) $this->request->getPost('quantity');
        if (! ctype_digit($productId) || ! ctype_digit($quantity) || strlen($quantity) > 10) {
            return redirect()->back()->with('error', 'Enter a valid quantity.');
        }
        try {
            (new CartService())->add((int) session('user_id'), (int) $productId, (int) $quantity);
            return redirect()->to('/cart')->with('success', 'Item added to your cart.');
        } catch (DomainException $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }

    public function update(int $id): RedirectResponse
    {
        $quantity = (string) $this->request->getPost('quantity');
        if (! ctype_digit($quantity) || strlen($quantity) > 10) { return redirect()->to('/cart')->with('error', 'Enter a valid quantity.'); }
        try {
            (new CartService())->update((int) session('user_id'), $id, (int) $quantity);
            return redirect()->to('/cart')->with('success', 'Cart updated.');
        } catch (DomainException $e) { return redirect()->to('/cart')->with('error', $e->getMessage()); }
    }

    public function remove(int $id): RedirectResponse
    {
        try { (new CartService())->remove((int) session('user_id'), $id); }
        catch (DomainException $e) { return redirect()->to('/cart')->with('error', $e->getMessage()); }
        return redirect()->to('/cart');
    }
}

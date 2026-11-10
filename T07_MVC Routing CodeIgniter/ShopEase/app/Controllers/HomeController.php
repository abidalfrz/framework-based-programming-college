<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        return view('frontend/home', [
            'title' => 'ShopEase | Everyday finds',
            'categories' => (new CategoryModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'products' => (new ProductModel())->select('products.*')->join('categories', 'categories.id = products.category_id')->where('categories.is_active', 1)->where('products.status', 'active')->orderBy('products.id', 'ASC')->findAll(4),
        ]);
    }

    public function about(): string
    {
        return view('frontend/info', ['title' => 'About ShopEase', 'page' => 'about']);
    }

    public function contact(): string
    {
        return view('frontend/info', ['title' => 'Contact ShopEase', 'page' => 'contact']);
    }

    public function credits(): string
    {
        return view('frontend/credits', ['title' => 'Photo credits | ShopEase']);
    }
}

<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductController extends BaseController
{
    public function index(): string
    {
        $query = trim((string) $this->request->getGet('q'));
        $category = (string) $this->request->getGet('category');
        $sort = (string) $this->request->getGet('sort');
        $model = new ProductModel();
        $model->select('products.*')->join('categories', 'categories.id = products.category_id')->where('products.status', 'active')->where('categories.is_active', 1);
        if ($query !== '') {
            $model->like('products.name', mb_substr($query, 0, 150));
        }
        if (ctype_digit($category) && (int) $category > 0) {
            $model->where('products.category_id', (int) $category);
        }
        $model->orderBy($sort === 'price_asc' || $sort === 'price_desc' ? 'products.price' : 'products.id', $sort === 'price_asc' ? 'ASC' : 'DESC');
        return view('frontend/catalog', ['title' => 'Browse products | ShopEase', 'products' => $model->paginate(12), 'pager' => $model->pager, 'categories' => (new CategoryModel())->where('is_active', 1)->findAll(), 'query' => $query, 'category' => $category, 'sort' => $sort]);
    }

    public function detail(string $slug): string
    {
        $product = (new ProductModel())->select('products.*')->join('categories', 'categories.id = products.category_id')->where('products.slug', $slug)->where('products.status', 'active')->where('categories.is_active', 1)->first();
        if (! $product) {
            throw PageNotFoundException::forPageNotFound();
        }
        return view('frontend/detail', ['title' => $product['name'] . ' | ShopEase', 'product' => $product]);
    }
}

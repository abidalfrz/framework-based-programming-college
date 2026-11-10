<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Exceptions\PageNotFoundException;

class CategoryController extends BaseController
{
    public function index(): string
    {
        $model = new CategoryModel();
        $query = trim((string) $this->request->getGet('q'));
        if ($query !== '') { $model->like('name', mb_substr($query, 0, 100)); }
        $categories = $model->orderBy('name')->paginate(12);
        return view('admin/categories', ['title' => 'Categories — ShopEase', 'categories' => $categories, 'pager' => $model->pager, 'query' => $query]);
    }

    public function save(?int $id = null): RedirectResponse
    {
        $model = new CategoryModel();
        if ($id && ! $model->find($id)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $rules = ['name' => 'required|min_length[2]|max_length[100]', 'slug' => 'required|alpha_dash|max_length[120]', 'description' => 'permit_empty|max_length[2000]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Check the category name, slug, and description.');
        }
        $slug = (string) $this->request->getPost('slug');
        $existing = $model->where('slug', $slug)->first();
        if ($existing && (int) $existing['id'] !== $id) {
            return redirect()->back()->withInput()->with('error', 'This slug is already in use.');
        }
        $data = ['name' => trim((string) $this->request->getPost('name')), 'slug' => $slug, 'description' => (string) $this->request->getPost('description')];
        $id ? $model->update($id, $data) : $model->insert($data);
        return redirect()->to('/admin/categories')->with('success', 'Category saved.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $model = new CategoryModel();
        $category = $model->find($id);
        if (! $category) {
            throw PageNotFoundException::forPageNotFound();
        }
        if ($category['is_active'] && (new ProductModel())->where('category_id', $id)->where('status', 'active')->countAllResults() > 0) {
            return redirect()->to('/admin/categories')->with('error', 'Deactivate active products in this category first.');
        }
        $model->update($id, ['is_active' => ! $category['is_active']]);
        return redirect()->to('/admin/categories')->with('success', 'Category status updated.');
    }
}

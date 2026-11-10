<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductController extends BaseController
{
    public function index(): string
    {
        $model = new ProductModel();
        $query = trim((string) $this->request->getGet('q'));
        $category = (string) $this->request->getGet('category');
        if ($query !== '') { $model->like('name', mb_substr($query, 0, 150)); }
        if (ctype_digit($category) && (int) $category > 0) { $model->where('category_id', (int) $category); }
        $products = $model->orderBy('id', 'DESC')->paginate(12);
        return view('admin/products', ['title' => 'Products — ShopEase', 'products' => $products, 'pager' => $model->pager, 'query' => $query, 'category' => $category, 'categories' => (new CategoryModel())->where('is_active', 1)->findAll()]);
    }

    public function save(?int $id = null): RedirectResponse
    {
        $model = new ProductModel();
        $old = $id ? $model->find($id) : null;
        if ($id && ! $old) {
            throw PageNotFoundException::forPageNotFound();
        }
        $rules = ['name' => 'required|min_length[2]|max_length[150]', 'slug' => 'required|alpha_dash|max_length[180]', 'category_id' => 'required|is_natural_no_zero', 'description' => 'permit_empty|max_length[5000]', 'price' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[9999999999.99]', 'stock' => 'required|is_natural|less_than_equal_to[4294967295]', 'status' => 'required|in_list[active,inactive]'];
        $price = trim((string) $this->request->getPost('price'));
        if (! preg_match('/^\d{1,10}(?:\.\d{1,2})?$/', $price) || ! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Check the product details. Price must have at most two decimal places.');
        }
        $category = (new CategoryModel())->where('is_active', 1)->find((int) $this->request->getPost('category_id'));
        $existing = $model->where('slug', (string) $this->request->getPost('slug'))->first();
        if (! $category || ($existing && (int) $existing['id'] !== $id)) {
            return redirect()->back()->withInput()->with('error', 'Choose an active category and a unique slug.');
        }
        $data = $this->request->getPost(array_keys($rules));
        $file = $this->request->getFile('image');
        $newPath = null;
        if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $this->validate(['image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|ext_in[image,jpg,jpeg,png,webp]|max_size[image,2048]'])) {
                return redirect()->back()->withInput()->with('error', 'Use a JPEG, PNG, or WebP image up to 2 MB.');
            }
            $dir = FCPATH . 'uploads/products/';
            if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
                return redirect()->back()->with('error', 'Could not save the image.');
            }
            $name = $file->getRandomName();
            $file->move($dir, $name);
            $data['image'] = $file->getName();
            $newPath = $dir . $data['image'];
        }
        try {
            $saved = $id ? $model->update($id, $data) : $model->insert($data);
            if ($saved === false) { throw new \RuntimeException('Unable to save product.'); }
        } catch (\Throwable $e) {
            if ($newPath && is_file($newPath)) { unlink($newPath); }
            throw $e;
        }
        if ($newPath && ! empty($old['image']) && is_file(FCPATH . 'uploads/products/' . basename($old['image']))) {
            unlink(FCPATH . 'uploads/products/' . basename($old['image']));
        }
        return redirect()->to('/admin/products')->with('success', 'Product saved.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $model = new ProductModel();
        $product = $model->find($id);
        if (! $product) { throw PageNotFoundException::forPageNotFound(); }
        if ($product['status'] !== 'active' && ! (new CategoryModel())->where('is_active', 1)->find($product['category_id'])) {
            return redirect()->to('/admin/products')->with('error', 'Activate the category before activating this product.');
        }
        $model->update($id, ['status' => $product['status'] === 'active' ? 'inactive' : 'active']);
        return redirect()->to('/admin/products')->with('success', 'Product status updated.');
    }
}

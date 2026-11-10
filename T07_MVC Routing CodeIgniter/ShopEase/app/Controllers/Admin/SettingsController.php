<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use CodeIgniter\HTTP\RedirectResponse;

class SettingsController extends BaseController
{
    public function index(): string
    {
        return view('admin/settings', ['title' => 'Store settings — ShopEase', 'settings' => (new SettingModel())->first()]);
    }

    public function save(): RedirectResponse
    {
        $shipping = trim((string) $this->request->getPost('shipping_cost'));
        $instructions = trim((string) $this->request->getPost('payment_instructions'));
        if (! preg_match('/^(?:0|[1-9]\d{0,9})(?:\.\d{1,2})?$/', $shipping) || mb_strlen($instructions) > 4000) {
            return redirect()->back()->withInput()->with('error', 'Enter a valid non-negative shipping cost and payment instructions under 4,000 characters.');
        }
        $model = new SettingModel();
        $existing = $model->first();
        $data = ['shipping_cost' => $shipping, 'payment_instructions' => $instructions ?: null, 'checkout_ready' => $instructions !== '' ? 1 : 0, 'updated_at' => date('Y-m-d H:i:s')];
        $existing ? $model->update($existing['id'], $data) : $model->insert($data);
        return redirect()->to('/admin/settings')->with('success', 'Store settings saved.');
    }
}

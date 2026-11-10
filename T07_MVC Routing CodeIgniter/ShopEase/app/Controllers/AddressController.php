<?php

namespace App\Controllers;

use App\Models\AddressModel;
use CodeIgniter\HTTP\RedirectResponse;

class AddressController extends BaseController
{
    public function index(): string
    {
        return view('account/addresses', ['title' => 'Your addresses — ShopEase', 'addresses' => (new AddressModel())->where('user_id', (int) session('user_id'))->findAll()]);
    }

    public function create(): RedirectResponse
    {
        $rules = ['recipient_name' => 'required|max_length[100]', 'phone' => 'required|max_length[30]', 'address' => 'required|max_length[2000]', 'city' => 'required|max_length[100]', 'province' => 'required|max_length[100]', 'postal_code' => 'required|max_length[20]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(array_keys($rules));
        $data['user_id'] = (int) session('user_id');
        (new AddressModel())->insert($data);
        return redirect()->to('/account/addresses')->with('success', 'Address saved.');
    }

    public function update(int $id): RedirectResponse
    {
        $model = new AddressModel();
        if (! $model->where('user_id', (int) session('user_id'))->find($id)) {
            return redirect()->to('/account/addresses')->with('error', 'Address not found.');
        }
        $rules = ['recipient_name' => 'required|max_length[100]', 'phone' => 'required|max_length[30]', 'address' => 'required|max_length[2000]', 'city' => 'required|max_length[100]', 'province' => 'required|max_length[100]', 'postal_code' => 'required|max_length[20]'];
        if (! $this->validate($rules)) {
            return redirect()->to('/account/addresses')->with('errors', $this->validator->getErrors());
        }
        $model->update($id, $this->request->getPost(array_keys($rules)));
        return redirect()->to('/account/addresses')->with('success', 'Address updated.');
    }

    public function setDefault(int $id): RedirectResponse
    {
        $db = \Config\Database::connect();
        $userId = (int) session('user_id');
        $db->transBegin();
        try {
            $users = $db->protectIdentifiers($db->prefixTable('users'));
            $db->query("SELECT id FROM {$users} WHERE id = ? FOR UPDATE", [$userId]);
            $address = $db->table('addresses')->where('id', $id)->where('user_id', $userId)->get()->getRowArray();
            if (! $address) { throw new \DomainException('Address not found.'); }
            $db->table('addresses')->where('user_id', $userId)->update(['is_default' => 0]);
            $db->table('addresses')->where('id', $id)->where('user_id', $userId)->update(['is_default' => 1]);
            if (! $db->transStatus()) { throw new \DomainException('Could not update the address.'); }
            $db->transCommit();
            return redirect()->to('/account/addresses')->with('success', 'Default address updated.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/account/addresses')->with('error', 'Could not update the address.');
        }
    }

    public function delete(int $id): RedirectResponse
    {
        $model = new AddressModel();
        $address = $model->where('user_id', (int) session('user_id'))->find($id);
        if ($address) {
            $model->delete($id);
        }
        return redirect()->to('/account/addresses');
    }
}

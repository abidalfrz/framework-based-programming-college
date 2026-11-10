<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Exceptions\PageNotFoundException;

class AccountController extends BaseController
{
    public function index(): string
    {
        $user = (new UserModel())->find((int) session('user_id'));
        if (! $user) { throw PageNotFoundException::forPageNotFound(); }
        return view('account/index', ['title' => 'Your account — ShopEase', 'user' => $user]);
    }

    public function password(): RedirectResponse
    {
        $model = new UserModel();
        $user = $model->find((int) session('user_id'));
        $new = (string) $this->request->getPost('password');
        $confirm = (string) $this->request->getPost('password_confirm');
        if (! $user || ! password_verify((string) $this->request->getPost('current_password'), $user['password_hash']) || strlen($new) < 8 || strlen($new) > 72 || $new !== $confirm) {
            return redirect()->to('/account')->with('error', 'Check your current password and new password.');
        }
        $model->update($user['id'], ['password_hash' => password_hash($new, PASSWORD_DEFAULT)]);
        session()->regenerate(true);
        return redirect()->to('/account')->with('success', 'Password updated.');
    }
}

<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    public function login(): string
    {
        return view('auth/login', ['title' => 'Sign in to ShopEase', 'returnTo' => $this->safeReturn((string) $this->request->getGet('return_to'))]);
    }

    public function register(): string
    {
        return view('auth/register', ['title' => 'Create an account']);
    }

    public function create(): RedirectResponse
    {
        $rules = ['name' => 'required|min_length[2]|max_length[100]', 'email' => 'required|valid_email|max_length[190]|is_unique[users.email]', 'password' => 'required|min_length[8]|max_length[72]', 'password_confirm' => 'required|matches[password]'];
        $password = (string) $this->request->getPost('password');
        if (strlen($password) > 72) {
            return redirect()->back()->with('errors', ['password' => 'Password must be at most 72 bytes.']);
        }
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->with('old_name', (string) $this->request->getPost('name'))->with('old_email', (string) $this->request->getPost('email'));
        }
        (new UserModel())->insert(['name' => trim((string) $this->request->getPost('name')), 'email' => strtolower(trim((string) $this->request->getPost('email'))), 'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT), 'role' => 'customer']);
        return redirect()->to('/login')->with('success', 'Account created. Please sign in.');
    }

    public function attempt(): RedirectResponse
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $user = (new UserModel())->where('email', $email)->first();
        if (! $user || ! $user['is_active'] || strlen($password) > 72 || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
        session()->regenerate(true);
        session()->set(['user_id' => (int) $user['id'], 'name' => $user['name'], 'role' => $user['role'], 'logged_in' => true]);
        $fallback = $user['role'] === 'admin' ? '/admin' : ($user['role'] === 'operator' ? '/operator/orders' : '/');
        return redirect()->to($this->safeReturn((string) $this->request->getPost('return_to')) ?: $fallback);
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/');
    }

    private function safeReturn(string $path): string
    {
        return str_starts_with($path, '/') && ! str_starts_with($path, '//') && ! str_contains($path, "\\") && ! preg_match('/[\x00-\x1f\x7f]/', $path) ? $path : '';
    }
}

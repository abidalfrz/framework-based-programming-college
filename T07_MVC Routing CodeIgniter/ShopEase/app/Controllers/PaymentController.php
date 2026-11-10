<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Throwable;

class PaymentController extends BaseController
{
    public function upload(int $id): RedirectResponse
    {
        $db = Database::connect();
        $order = $db->table('orders')->where('id', $id)->where('user_id', (int) session('user_id'))->get()->getRowArray();
        if (! $order) {
            throw PageNotFoundException::forPageNotFound();
        }
        if (! $this->validate(['proof' => 'uploaded[proof]|is_image[proof]|mime_in[proof,image/jpeg,image/png,image/webp]|ext_in[proof,jpg,jpeg,png,webp]|max_size[proof,2048]'])) {
            return redirect()->to('/orders/' . $id)->with('error', 'Please upload a JPEG, PNG, or WebP image no larger than 2 MB.');
        }
        $file = $this->request->getFile('proof');
        $dir = WRITEPATH . 'uploads/payment-proofs/';
        if (! is_dir($dir) && ! mkdir($dir, 0700, true) && ! is_dir($dir)) {
            return redirect()->to('/orders/' . $id)->with('error', 'The proof could not be saved. Please try again.');
        }
        $name = $file->getRandomName();
        $moved = false;
        try {
            $file->move($dir, $name);
            $name = $file->getName();
            $moved = true;
            $db->transBegin();
            $orders = $db->protectIdentifiers($db->prefixTable('orders'));
            $order = $db->query("SELECT * FROM {$orders} WHERE id = ? AND user_id = ? FOR UPDATE", [$id, (int) session('user_id')])->getRowArray();
            $payment = $db->table('payments')->where('order_id', $id)->get()->getRowArray();
            if (! $order || $order['order_status'] !== 'pending_payment' || ! $payment || ! in_array($payment['payment_status'], ['unpaid', 'rejected'], true)) {
                throw new \DomainException('Payment cannot be submitted for this order.');
            }
            $oldPath = $payment['proof_path'];
            $now = date('Y-m-d H:i:s');
            $db->table('payments')->where('id', $payment['id'])->update(['proof_path' => $name, 'payment_status' => 'submitted', 'rejection_reason' => null, 'updated_at' => $now]);
            $db->table('orders')->where('id', $id)->update(['order_status' => 'waiting_verification', 'updated_at' => $now]);
            $db->table('order_status_events')->insert(['order_id' => $id, 'actor_id' => (int) session('user_id'), 'old_status' => 'pending_payment', 'new_status' => 'waiting_verification', 'created_at' => $now]);
            if (! $db->transStatus()) {
                throw new \RuntimeException('Unable to save payment proof.');
            }
            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            if ($moved && is_file($dir . $name)) {
                unlink($dir . $name);
            }
            log_message('error', 'Payment proof upload failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/orders/' . $id)->with('error', 'Payment proof could not be submitted. Please try again.');
        }
        if ($oldPath && is_file($dir . basename($oldPath)) && ! @unlink($dir . basename($oldPath))) {
            log_message('warning', 'Could not remove an old payment proof for order {id}.', ['id' => $id]);
        }
        return redirect()->to('/orders/' . $id)->with('success', 'Payment proof submitted for review.');
    }

    public function proof(int $id): ResponseInterface
    {
        $db = Database::connect();
        $order = $db->table('orders')->where('id', $id)->get()->getRowArray();
        $role = session('role');
        if (! $order || ($role !== 'admin' && ! ($role === 'customer' && (int) $order['user_id'] === (int) session('user_id')))) {
            throw PageNotFoundException::forPageNotFound();
        }
        $payment = $db->table('payments')->where('order_id', $id)->get()->getRowArray();
        $name = $payment['proof_path'] ?? '';
        $path = WRITEPATH . 'uploads/payment-proofs/' . basename($name);
        if ($name === '' || ! is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }
        return $this->response->download($path, null)->setFileName('payment-proof.' . pathinfo($path, PATHINFO_EXTENSION));
    }
}

<?php

namespace App\Services;

use Config\Database;
use DomainException;
use Throwable;

class OrderService
{
    public function cancel(int $orderId, int $actorId): void
    {
        $this->transition($orderId, $actorId, ['admin'], function ($db, $order): string {
            if (! in_array($order['order_status'], ['pending_payment', 'waiting_verification'], true)) {
                throw new DomainException('This order cannot be cancelled.');
            }
            $items = $db->table('order_items')->where('order_id', $order['id'])->orderBy('product_id', 'ASC')->get()->getResultArray();
            foreach ($items as $item) {
                $db->table('products')->where('id', $item['product_id'])->set('stock', 'stock + ' . (int) $item['quantity'], false)->update();
            }
            return 'cancelled';
        });
    }

    public function verifyPayment(int $orderId, int $actorId, bool $approve, ?string $reason): void
    {
        $this->transition($orderId, $actorId, ['admin'], function ($db, $order) use ($approve, $reason, $actorId): string {
            $payment = $db->table('payments')->where('order_id', $order['id'])->get()->getRowArray();
            if ($order['order_status'] !== 'waiting_verification' || ! $payment || $payment['payment_status'] !== 'submitted' || empty($payment['proof_path'])) {
                throw new DomainException('Payment is not awaiting verification.');
            }
            if (! $approve && trim((string) $reason) === '') {
                throw new DomainException('A rejection reason is required.');
            }
            $db->table('payments')->where('id', $payment['id'])->update(['payment_status' => $approve ? 'verified' : 'rejected', 'rejection_reason' => $approve ? null : trim((string) $reason), 'verified_by' => $actorId, 'verified_at' => date('Y-m-d H:i:s')]);
            return $approve ? 'processing' : 'pending_payment';
        });
    }

    public function ship(int $orderId, int $actorId, string $courier, string $trackingNumber): void
    {
        $this->transition($orderId, $actorId, ['admin', 'operator'], function ($db, $order) use ($courier, $trackingNumber): string {
            $payment = $db->table('payments')->where('order_id', $order['id'])->get()->getRowArray();
            if ($order['order_status'] !== 'processing' || ! $payment || $payment['payment_status'] !== 'verified' || trim($courier) === '' || trim($trackingNumber) === '' || mb_strlen($courier) > 100 || mb_strlen($trackingNumber) > 100) {
                throw new DomainException('This order cannot be shipped with the provided details.');
            }
            $db->table('shipments')->insert(['order_id' => $order['id'], 'courier' => trim($courier), 'tracking_number' => trim($trackingNumber), 'shipped_at' => date('Y-m-d H:i:s')]);
            return 'shipped';
        });
    }

    public function complete(int $orderId, int $actorId): void
    {
        $this->transition($orderId, $actorId, ['admin', 'operator'], function ($db, $order): string {
            if ($order['order_status'] !== 'shipped') {
                throw new DomainException('Only shipped orders can be completed.');
            }
            $db->table('shipments')->where('order_id', $order['id'])->update(['delivered_at' => date('Y-m-d H:i:s')]);
            return 'completed';
        });
    }

    private function transition(int $orderId, int $actorId, array $roles, callable $action): void
    {
        $db = Database::connect();
        $actor = $db->table('users')->where('id', $actorId)->where('is_active', 1)->get()->getRowArray();
        if (! $actor || ! in_array($actor['role'], $roles, true)) {
            throw new DomainException('You are not allowed to perform this action.');
        }
        $db->transBegin();
        try {
            $table = $db->protectIdentifiers($db->prefixTable('orders'));
            $order = $db->query("SELECT * FROM {$table} WHERE id = ? FOR UPDATE", [$orderId])->getRowArray();
            if (! $order) {
                throw new DomainException('Order not found.');
            }
            $next = $action($db, $order);
            $now = date('Y-m-d H:i:s');
            $db->table('orders')->where('id', $orderId)->update(['order_status' => $next, 'updated_at' => $now]);
            $db->table('order_status_events')->insert(['order_id' => $orderId, 'actor_id' => $actorId, 'old_status' => $order['order_status'], 'new_status' => $next, 'created_at' => $now]);
            if (! $db->transStatus()) {
                throw new DomainException('The order could not be updated.');
            }
            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}

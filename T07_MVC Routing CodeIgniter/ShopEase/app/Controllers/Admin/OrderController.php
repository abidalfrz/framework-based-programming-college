<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\PaymentModel;
use App\Models\ShipmentModel;
use App\Services\OrderService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

class OrderController extends BaseController
{
    public function index(): string
    {
        $model = new OrderModel();
        if (session('role') === 'operator') { $model->whereIn('order_status', ['processing', 'shipped', 'completed']); }
        $query = trim((string) $this->request->getGet('q'));
        $status = (string) $this->request->getGet('status');
        $from = (string) $this->request->getGet('from');
        $to = (string) $this->request->getGet('to');
        $validDate = static function (string $value): bool { $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value); return $date && $date->format('Y-m-d') === $value; };
        $error = null;
        if (($from !== '' && ! $validDate($from)) || ($to !== '' && ! $validDate($to)) || ($from !== '' && $to !== '' && $from > $to)) { $error = 'Choose a valid date range.'; $from = $to = ''; }
        if ($query !== '') { $model->like('order_number', mb_substr($query, 0, 50)); }
        $statuses = ['pending_payment', 'waiting_verification', 'processing', 'shipped', 'completed', 'cancelled'];
        if (in_array($status, $statuses, true)) { $model->where('order_status', $status); } else { $status = ''; }
        if ($from !== '') { $model->where('created_at >=', $from . ' 00:00:00'); }
        if ($to !== '') { $model->where('created_at <=', $to . ' 23:59:59'); }
        $orders = $model->orderBy('id', 'DESC')->paginate(12);
        return view('admin/orders/index', ['title' => 'Orders — ShopEase', 'orders' => $orders, 'pager' => $model->pager, 'prefix' => $this->prefix(), 'query' => $query, 'status' => $status, 'from' => $from, 'to' => $to, 'statuses' => $statuses, 'error' => $error]);
    }

    public function show(int $id): string
    {
        $order = (new OrderModel())->find($id);
        if (! $order || (session('role') === 'operator' && ! in_array($order['order_status'], ['processing', 'shipped', 'completed'], true))) {
            throw PageNotFoundException::forPageNotFound();
        }
        return view('admin/orders/show', ['title' => 'Order ' . $order['order_number'], 'order' => $order, 'items' => (new OrderItemModel())->where('order_id', $id)->findAll(), 'payment' => (new PaymentModel())->where('order_id', $id)->first(), 'shipment' => (new ShipmentModel())->where('order_id', $id)->first(), 'prefix' => $this->prefix()]);
    }

    public function verify(int $id): RedirectResponse
    {
        $decision = (string) $this->request->getPost('decision');
        if (! in_array($decision, ['approve', 'reject'], true)) { return redirect()->back()->with('error', 'Choose a valid decision.'); }
        return $this->run($id, fn () => (new OrderService())->verifyPayment($id, (int) session('user_id'), $decision === 'approve', (string) $this->request->getPost('reason')));
    }

    public function cancel(int $id): RedirectResponse
    {
        return $this->run($id, fn () => (new OrderService())->cancel($id, (int) session('user_id')));
    }

    public function ship(int $id): RedirectResponse
    {
        return $this->run($id, fn () => (new OrderService())->ship($id, (int) session('user_id'), (string) $this->request->getPost('courier'), (string) $this->request->getPost('tracking_number')));
    }

    public function complete(int $id): RedirectResponse
    {
        return $this->run($id, fn () => (new OrderService())->complete($id, (int) session('user_id')));
    }

    private function run(int $id, callable $action): RedirectResponse
    {
        try { $action(); return redirect()->to('/' . $this->prefix() . '/orders/' . $id)->with('success', 'Order updated.'); }
        catch (DomainException $e) { return redirect()->to('/' . $this->prefix() . '/orders/' . $id)->with('error', $e->getMessage()); }
    }

    private function prefix(): string
    {
        return session('role') === 'operator' ? 'operator' : 'admin';
    }
}

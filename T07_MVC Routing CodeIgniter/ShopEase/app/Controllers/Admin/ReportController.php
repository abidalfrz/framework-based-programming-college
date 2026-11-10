<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class ReportController extends BaseController
{
    public function index(): string
    {
        $from = (string) $this->request->getGet('from');
        $to = (string) $this->request->getGet('to');
        $validDate = static function (string $value): bool {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
            return $date && $date->format('Y-m-d') === $value;
        };
        if (($from !== '' && ! $validDate($from)) || ($to !== '' && ! $validDate($to)) || ($from !== '' && $to !== '' && $from > $to)) {
            return view('admin/reports', ['title' => 'Sales report — ShopEase', 'from' => '', 'to' => '', 'summary' => ['total' => '0.00', 'count' => 0], 'error' => 'Choose a valid date range.']);
        }
        $query = Database::connect()->table('orders')->select('COALESCE(SUM(total),0) AS total, COUNT(*) AS count', false)->whereIn('order_status', ['processing', 'shipped', 'completed']);
        if ($from !== '') { $query->where('created_at >=', $from . ' 00:00:00'); }
        if ($to !== '') { $query->where('created_at <=', $to . ' 23:59:59'); }
        return view('admin/reports', ['title' => 'Sales report — ShopEase', 'from' => $from, 'to' => $to, 'summary' => $query->get()->getRowArray(), 'error' => null]);
    }
}

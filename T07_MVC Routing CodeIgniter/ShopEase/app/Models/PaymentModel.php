<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $allowedFields = ['order_id', 'amount', 'payment_status', 'proof_path', 'rejection_reason', 'verified_by', 'verified_at'];
    protected $useTimestamps = true;
}

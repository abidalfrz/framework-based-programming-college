<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'addresses';
    protected $allowedFields = ['user_id', 'recipient_name', 'phone', 'address', 'city', 'province', 'postal_code', 'is_default'];
    protected $useTimestamps = true;
}

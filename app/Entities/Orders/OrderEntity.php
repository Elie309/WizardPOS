<?php

namespace App\Entities\Orders;

use CodeIgniter\Entity\Entity;

class OrderEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = [
        'order_created_at', 
        'order_updated_at', 
        'order_deleted_at'];
    protected $casts   = [];
}

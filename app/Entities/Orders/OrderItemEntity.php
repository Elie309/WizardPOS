<?php

namespace App\Entities\Orders;

use CodeIgniter\Entity\Entity;

class OrderItemEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = [
        'order_item_created_at', 
        'order_item_updated_at', 
        'order_item_deleted_at'];
    protected $casts   = [];
}

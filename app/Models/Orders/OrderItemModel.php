<?php

namespace App\Models\Orders;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'order_item_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Orders\OrderItemEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'order_item_product_id',
        'order_item_quantity',
        'order_item_total',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'order_item_created_at';
    protected $updatedField  = 'order_item_updated_at';
    protected $deletedField  = 'order_item_deleted_at';

    // Validation
    protected $validationRules      = [
        'order_id' => 'required|integer',
        'order_item_product_id' => 'required|integer',
        'order_item_quantity' => 'required|integer',
        'order_item_total' => 'required|decimal',
    ];
    protected $validationMessages   = [
        'order_id' => [
            'required' => 'Order ID is required',
            'integer' => 'Order ID should be an integer',
        ],
        'order_item_product_id' => [
            'required' => 'Product ID is required',
            'integer' => 'Product ID should be an integer',
        ],
        'order_item_quantity' => [
            'required' => 'Quantity is required',
            'integer' => 'Quantity should be an integer',
        ],
        'order_item_total' => [
            'required' => 'Total is required',
            'decimal' => 'Total should be a decimal',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

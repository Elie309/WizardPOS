<?php

namespace App\Models\Orders;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Orders\OrderEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_client_id',
        'order_employee_id',
        'order_type',
        'order_reference',
        'order_date',
        'order_time',
        'order_note',
        'order_subtotal',
        'order_discount',
        'order_tax',
        'order_total',
        'order_status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'order_created_at';
    protected $updatedField  = 'order_updated_at';
    protected $deletedField  = 'order_deleted_at';

    // Validation
    protected $validationRules      = [
        'order_client_id'   => 'required|integer',
        'order_employee_id' => 'required|integer',
        'order_type'        => 'required|in_list[take-away,dine-in,delivery]',
        'order_reference'   => 'required|string|is_unique[orders.order_reference]',
        'order_date'        => 'required|valid_date',
        'order_time'        => 'required|valid_date',
        'order_note'        => 'permit_empty|string',
        'order_subtotal'    => 'required|decimal',
        'order_discount'    => 'required|decimal',
        'order_tax'         => 'required|decimal',
        'order_total'       => 'required|decimal',
        'order_status'      => 'required|in_list[on-going,completed,cancelled]',
    ];
    protected $validationMessages   = [
        'order_client_id' => [
            'required' => 'Client ID is required',
            'integer' => 'Client ID should be an integer',
        ],
        'order_employee_id' => [
            'required' => 'Employee ID is required',
            'integer' => 'Employee ID should be an integer',
        ],
        'order_type' => [
            'required' => 'Type is required',
            'in_list' => 'Type is not valid, should be one of: take-away, dine-in, delivery',
        ],
        'order_reference' => [
            'required' => 'Reference is required',
            'is_unique' => 'Reference already exists',
            'string' => 'Reference is not valid',
        ],
        'order_date' => [
            'required' => 'Date is required',
            'valid_date' => 'Date is not valid',
        ],
        'order_time' => [
            'required' => 'Time is required',
            'valid_time' => 'Time is not valid',
        ],
        'order_note' => [
            'permit_empty' => 'Note is not valid',
            'string' => 'Note is not valid',
        ],
        'order_subtotal' => [
            'required' => 'Subtotal is required',
            'decimal' => 'Subtotal is not valid',
        ],
        'order_discount' => [
            'required' => 'Discount is required',
            'decimal' => 'Discount is not valid',
        ],
        'order_tax' => [
            'required' => 'Tax is required',
            'decimal' => 'Tax is not valid',
        ],
        'order_total' => [
            'required' => 'Total is required',
            'decimal' => 'Total is not valid',
        ],
        'order_status' => [
            'required' => 'Status is required',
            'in_list' => 'Status is not valid',
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

    public function generateReference($order_id): string
    {
        $newReference = "ORD-" . date('Y') . "-" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
        return $newReference;
    }
}

<?php

namespace App\Models\Products;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'product_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Products\ProductEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_sku',
        'product_slug',
        'product_name',
        'product_description',
        'product_price',
        'product_category_id',
        'product_production_date',
        'product_expiry_date',
        'product_image',
        'product_is_active',

    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'product_created_at';
    protected $updatedField  = 'product_updated_at';
    protected $deletedField  = 'product_deleted_at';

    // Validation
    protected $validationRules      = [
        'product_sku' => 'required|alpha_numeric|max_length[50]|is_unique[products.product_sku]',
        'product_slug' => 'required|alpha_numeric|max_length[100]|is_unique[products.product_slug]',
        'product_name' => 'required|alpha_numeric_space|max_length[100]',
        'product_description' => 'alpha_numeric_space',
        'product_price' => 'required|decimal',
        'product_category_id' => 'required|integer',
        'product_production_date' => 'valid_date',
        'product_expiry_date' => 'valid_date',
        'product_image' => 'valid_url',
        'product_is_active' => 'boolean',
    ];
    protected $validationMessages   = [
        'product_sku' => [
            'required' => 'Product SKU is required',
            'alpha_numeric' => 'Product SKU must be alphanumeric',
            'max_length' => 'Product SKU must not exceed 50 characters',
            'is_unique' => 'Product SKU must be unique',
        ],
        'product_slug' => [
            'required' => 'Product Slug is required',
            'alpha_numeric' => 'Product Slug must be alphanumeric',
            'max_length' => 'Product Slug must not exceed 100 characters',
            'is_unique' => 'Product Slug must be unique',
        ],
        'product_name' => [
            'required' => 'Product Name is required',
            'alpha_numeric_space' => 'Product Name must be alphanumeric with spaces',
            'max_length' => 'Product Name must not exceed 100 characters',
        ],
        'product_description' => [
            'alpha_numeric_space' => 'Product Description must be alphanumeric with spaces',
        ],
        'product_price' => [
            'required' => 'Product Price is required',
            'decimal' => 'Product Price must be a decimal',
        ],
        'product_category_id' => [
            'required' => 'Product Category ID is required',
            'integer' => 'Product Category ID must be an integer',
        ],
        'product_production_date' => [
            'valid_date' => 'Product Production Date must be a valid date',
        ],
        'product_expiry_date' => [
            'valid_date' => 'Product Expiry Date must be a valid date',
        ],
        'product_image' => [
            'valid_url' => 'Product Image must be a valid URL',
        ],
        'product_is_active' => [
            'boolean' => 'Product Is Active must be a boolean',
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

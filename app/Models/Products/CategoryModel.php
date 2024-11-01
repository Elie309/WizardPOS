<?php

namespace App\Models;

use CodeIgniter\Model;




class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'category_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Products\CategoryEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_name',
        'category_description',
        'category_image',
        'category_is_active',
        'category_show_in_menu',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'category_created_at';
    protected $updatedField  = 'category_updated_at';
    protected $deletedField  = 'category_deleted_at';

    // Validation
    protected $validationRules      = [
        'category_id' => 'permit_empty|integer',
        'category_name' => 'required|string|max_length[50]|is_unique[categories.category_name]',
        'category_description' => 'permit_empty|string',
        'category_image' => 'permit_empty|string|max_length[255]',
        'category_is_active' => 'required|boolean',
        'category_show_in_menu' => 'permit_empty|boolean',
    ];
    protected $validationMessages   = [
        'category_name' => [
            'is_unique' => 'Category name already exists',
        ],
        'category_image' => [
            'string' => 'Category image should be a string',
            'max_length' => 'Category image should not exceed 255 characters',
        ],
        'category_is_active' => [
            'required' => 'Category status is required',
            'boolean' => 'Category status should be a boolean',
        ],
        'category_id' => [
            'integer' => 'Category ID should be an integer',
        ],
        'category_description' => [
            'string' => 'Category description should be a string',
        ],
        'category_show_in_menu' => [
            'boolean' => 'Category show in menu should be a boolean',
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

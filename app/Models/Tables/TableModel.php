<?php

namespace App\Models\Tables;

use CodeIgniter\Model;

class TableModel extends Model
{
    protected $table            = 'restaurant_tables';
    protected $primaryKey       = 'table_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Tables\TableEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'table_name',
        'table_description',
        'table_max_capacity',
        'table_is_active',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'table_created_at';
    protected $updatedField  = 'table_updated_at';
    protected $deletedField  = 'table_deleted_at';

    // Validation
    protected $validationRules      = [
        'table_name' => 'required|alpha_numeric_space|max_length[50]',
        'table_description' => 'permit_empty|string',
        'table_max_capacity' => 'required|integer',
        'table_is_active' => 'permit_empty|boolean',
    ];
    protected $validationMessages   = [
        'table_name' => [
            'required' => 'Table name is required',
            'alpha_numeric_space' => 'Table name must be alphanumeric with spaces',
            'max_length' => 'Table name must not exceed 50 characters',
        ],
        'table_max_capacity' => [
            'required' => 'Table max capacity is required',
            'integer' => 'Table max capacity must be an integer',
        ],
        'table_is_active' => [
            'boolean' => 'Table is active must be a boolean',
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

<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'employee_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\EmployeeEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employee_number',
        'employee_first_name',
        'employee_last_name',
        'employee_email',
        'employee_role',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'employee_created_at';
    protected $updatedField  = 'employee_updated_at';
    protected $deletedField  = 'employee_deleted_at';

    // Validation
    protected $validationRules      = [
        'employee_id'           => 'permit_empty|numeric',
        'employee_number'       => 'required|numeric|max_length[15]|is_unique[employees.employee_number]',
        'employee_email'        => 'required|valid_email|max_length[100]|is_unique[employees.employee_email]',
        'employee_password'     => 'required|string|max_length[255]',
        'employee_first_name'   => 'required|string|max_length[50]',
        'employee_last_name'    => 'required|string|max_length[50]',
        'employee_role'         => 'required|string|in_list[admin,manager,user]',
    ];
    protected $validationMessages   = [
        'employee_id'           => [
            'numeric'       => 'The employee ID must be a number.',
        ],
        'employee_number'       => [
            'required'      => 'The employee number field is required.',
            'numeric'       => 'The employee number must be a number.',
            'max_length'    => 'The employee number must not exceed 15 characters.',
            'is_unique'     => 'The employee number is already taken.',
        ],
        'employee_email'        => [
            'required'      => 'The employee email field is required.',
            'valid_email'   => 'The employee email must be a valid email address.',
            'max_length'    => 'The employee email must not exceed 100 characters.',
            'is_unique'     => 'The employee email is already taken.',
        ],
        'employee_password'     => [
            'required'      => 'The employee password field is required.',
            'string'        => 'The employee password must be a string.',
            'max_length'    => 'The employee password must not exceed 255 characters.',
        ],
        'employee_first_name'   => [
            'required'      => 'The employee first name field is required.',
            'string'        => 'The employee first name must be a string.',
            'max_length'    => 'The employee first name must not exceed 50 characters.',
        ],
        'employee_last_name'    => [
            'required'      => 'The employee last name field is required.',
            'string'        => 'The employee last name must be a string.',
            'max_length'    => 'The employee last name must not exceed 50 characters.',
        ],
        'employee_role'         => [
            'required'      => 'The employee role field is required.',
            'string'        => 'The employee role must be a string.',
            'in_list'       => 'The employee role must be either admin, manager, or user.',
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

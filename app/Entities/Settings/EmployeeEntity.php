<?php

namespace App\Entities\Settings;

use CodeIgniter\Entity\Entity;

class EmployeeEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['employee_created_at', 'employee_updated_at', 'employee_deleted_at'];
    protected $casts   = [
        'employee_id'           => 'integer',
        'employee_phone_number'       => 'string',
        'employee_email'        => 'string',
        'employee_password'     => 'string',
        'employee_first_name'   => 'string',
        'employee_last_name'    => 'string',
        'employee_role'         => 'string',
        'employee_is_active'    => 'boolean',
    ];


    public function setPassword(string $pass)
    {
        $this->attributes['employee_password'] = password_hash($pass, PASSWORD_BCRYPT);

        return $this;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->attributes['employee_password']);
    }
}

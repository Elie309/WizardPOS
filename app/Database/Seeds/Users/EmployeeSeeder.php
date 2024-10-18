<?php

namespace App\Database\Seeds\Users;

use CodeIgniter\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'employee_number' => 1001,
                'employee_first_name' => 'John',
                'employee_last_name' => 'Doe',
                'employee_email' => 'johndoe@email.com',
                'employee_role' => 'admin',
                'employee_password' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'employee_number' => 1002,
                'employee_first_name' => 'Jane',
                'employee_last_name' => 'Doe',
                'employee_email' => 'janedoe@email.com',
                'employee_role' => 'manager',
                'employee_password' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'employee_number' => 1003,
                'employee_first_name' => 'John',
                'employee_last_name' => 'Smith',
                'employee_email' => 'johnsmith@email.com',
                'employee_role' => 'user',
                'employee_password' => password_hash('password', PASSWORD_BCRYPT),
            ],
        ];

        $this->db->table('employees')->insertBatch($data);
    }
}

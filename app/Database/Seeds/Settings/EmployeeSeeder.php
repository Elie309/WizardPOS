<?php

namespace App\Database\Seeds\Settings;

use CodeIgniter\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'employee_phone_number' => "+961 70 123456",
                'employee_first_name' => 'John',
                'employee_last_name' => 'Doe',
                'employee_email' => 'johndoe@email.com',
                'employee_role' => 'admin',
                'employee_password' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'employee_phone_number' => "+961 70 654321",
                'employee_first_name' => 'Jane',
                'employee_last_name' => 'Doe',
                'employee_email' => 'janedoe@email.com',
                'employee_role' => 'manager',
                'employee_password' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'employee_phone_number' => "+961 70 987654",
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

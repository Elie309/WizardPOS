<?php

namespace App\Database\Seeds\Clients;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'client_first_name' => 'Client ' . $i,
                'client_last_name' => 'Client ' . $i,
                'client_email' => 'client' . $i . '@gmail.com',
                'client_phone_number' => '1234567890' . $i,
                'client_address' => 'Client Address ' . $i,
                'client_is_active' => 1,
            ];

        }

        $this->db->table('clients')->insertBatch($data);

    }
}

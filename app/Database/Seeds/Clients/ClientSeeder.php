<?php

namespace App\Database\Seeds\Clients;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $data = [];
        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'client_first_name' => $faker->firstName,
                'client_last_name' => $faker->lastName,
                'client_email' => $faker->email,
                'client_phone_number' => $faker->phoneNumber,
                'client_address' => $faker->address,
                'client_is_active' => $faker->boolean,
            ];
        }

        $this->db->table('clients')->insertBatch($data);
    }
}

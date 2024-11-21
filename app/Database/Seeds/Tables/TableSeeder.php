<?php

namespace App\Database\Seeds\Tables;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class TableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 15; $i++) {
            $data = [
                'table_name' => $faker->word,
                'table_description' => $faker->sentence,
                'table_max_capacity' => $faker->numberBetween(2, 10),
                'table_is_active' => $faker->boolean,
            ];

            // Using Query Builder
            $this->db->table('restaurant_tables')->insert($data);
        }
    }
}

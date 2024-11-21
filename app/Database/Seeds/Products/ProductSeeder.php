<?php

namespace App\Database\Seeds\Products;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $categories = $this->db->table('categories')->get()->getResult();

        for ($i = 0; $i < 100; $i++) {
            $randomCategory = $categories[array_rand($categories)]->category_id;

            $products[] = [
                'product_sku' => $faker->numberBetween(1000000000000, 9999999999999),
                'product_slug' => $faker->slug,
                'product_name' => $faker->word,
                'product_description' => $faker->sentence,
                'product_price' => $faker->randomFloat(2, 5, 25),  // Random price between $5.00 and $25.00
                'product_category_id' => $randomCategory,
                'product_production_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'product_expiry_date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'product_show_in_menu' => $faker->boolean,
                'product_image' => $faker->imageUrl(640, 480, 'product'),
                'product_is_active' => 1,
            ];
        }

        $this->db->table('products')->insertBatch($products);
    }
}

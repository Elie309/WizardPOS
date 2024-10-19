<?php

namespace App\Database\Seeds\Products;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = $this->db->table('categories')->get()->getResult();

        for ($i = 10; $i < 100; $i++) {
            $randomCategory = $categories[array_rand($categories)]->category_id;
        
            $products[] = [
                'product_sku' => "SKU$i",
                'product_slug' => "product-$i",

                'product_name' => "Product $i",
                'product_description' => "This is a sample description for Product $i.",
                'product_price' => mt_rand(500, 2500) / 100,  // Random price between $5.00 and $25.00
                'product_category_id' => $randomCategory,
                'product_production_date' => date('Y-m-d', strtotime("-$i days")),
                'product_expiry_date' => date('Y-m-d', strtotime("+$i days")),
                'product_image' => "https://picsum.photos/seed/picsum/640/480",
                'product_is_active' => 1,
            ];
        }

        $this->db->table('products')->insertBatch($products);
    }
}

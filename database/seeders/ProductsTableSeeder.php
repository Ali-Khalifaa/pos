<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products=['pro one','pro two','pro three'];
        foreach ($products as $product) {
            Product::create([
                'ar'=>['name'=>$product,'description' => $product . 'desc'],
                'en'=>['name'=>$product,'description' => $product . 'desc'],
                'category_id' => 1,
                'purchase_price'=> 100,
                'sale_price'=> 150,
                'stock'=> 30,
            ]);
        }
    }
}

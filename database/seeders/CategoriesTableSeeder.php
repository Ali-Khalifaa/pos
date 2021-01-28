<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = ['cat one','cat two'];
        foreach ($categories as $category) {
            Category::create([
                'ar'=>['name'=>$category],
                'en'=>['name'=>$category],
            ]);
        }
    }
}

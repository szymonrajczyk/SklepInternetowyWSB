<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'name' => $category->name . ' Produkt ' . $i,
                    'description' => 'Opis przykładowego produktu z kategorii ' . $category->name . '.',
                    'price' => rand(50, 500),
                    'stock' => rand(1, 100),
                    'category_id' => $category->id,
                    'seller_id' => 1,
                    'image' => null,
                ]);
            }
        }
    }
}
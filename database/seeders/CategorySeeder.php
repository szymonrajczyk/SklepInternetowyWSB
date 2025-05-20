<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Elektronika',
                'description' => 'Urządzenia i gadżety elektroniczne.'
            ],
            [
                'name' => 'Ubrania',
                'description' => 'Odzież i artykuły modowe.'
            ],
            [
                'name' => 'Dom i kuchnia',
                'description' => 'Produkty dla Twojego domu i kuchnii.'
            ],
            [
                'name' => 'Książki',
                'description' => 'Książki, e-booki i publikacje.'
            ],
            [
                'name' => 'Sport i rekreacja',
                'description' => 'Sprzęt sportowy i wyposażenie outdoorowe.'
            ],
            [
                'name' => 'Uroda i higiena osobista',
                'description' => 'Produkty kosmetyczne i artykuły higieny osobistej.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

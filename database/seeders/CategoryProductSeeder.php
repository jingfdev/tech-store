<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Mouse', 'slug' => 'mouse', 'description' => 'Computer mice and accessories'],
            ['name' => 'Keyboard', 'slug' => 'keyboard', 'description' => 'Mechanical and membrane keyboards'],
            ['name' => 'Headset', 'slug' => 'headset', 'description' => 'Gaming and professional headsets'],
            ['name' => 'Monitor', 'slug' => 'monitor', 'description' => 'Gaming and professional monitors'],
            ['name' => 'Speakers', 'slug' => 'speakers', 'description' => 'Desktop and portable speakers'],
        ];

        foreach ($categories as $categoryData) {
            $category = \App\Models\Category::create($categoryData);
        }

        // Create products with specific category assignments
        $products = [
            ['name' => 'Logitech MX Master 3', 'description' => 'Advanced wireless mouse for professionals', 'price' => 99.99, 'image' => 'https://via.placeholder.com/400x300?text=Logitech+MX+Master+3', 'category_name' => 'Mouse'],
            ['name' => 'Corsair K95 RGB Platinum', 'description' => 'Mechanical gaming keyboard with RGB lighting', 'price' => 199.99, 'image' => 'https://via.placeholder.com/400x300?text=Corsair+K95+RGB', 'category_name' => 'Keyboard'],
            ['name' => 'SteelSeries Arctis 7', 'description' => 'Wireless gaming headset with DTS 7.1 surround', 'price' => 149.99, 'image' => 'https://via.placeholder.com/400x300?text=SteelSeries+Arctis+7', 'category_name' => 'Headset'],
            ['name' => 'ASUS ROG Swift PG279Q', 'description' => '27-inch 1440p 165Hz gaming monitor', 'price' => 699.99, 'image' => 'https://via.placeholder.com/400x300?text=ASUS+ROG+Swift', 'category_name' => 'Monitor'],
            ['name' => 'Audioengine A2+', 'description' => 'Premium desktop speakers with built-in DAC', 'price' => 249.99, 'image' => 'https://via.placeholder.com/400x300?text=Audioengine+A2%2B', 'category_name' => 'Speakers'],
        ];

        foreach ($products as $productData) {
            $category = \App\Models\Category::where('name', $productData['category_name'])->first();
            unset($productData['category_name']);
            
            \App\Models\Product::create(array_merge($productData, [
                'category_id' => $category->id,
            ]));
        }
    }
}

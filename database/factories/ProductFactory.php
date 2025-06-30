<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $products = [
            ['name' => 'Logitech MX Master 3', 'description' => 'Advanced wireless mouse for professionals', 'price' => 99.99, 'image' => 'https://via.placeholder.com/400x300?text=Logitech+MX+Master+3'],
            ['name' => 'Corsair K95 RGB Platinum', 'description' => 'Mechanical gaming keyboard with RGB lighting', 'price' => 199.99, 'image' => 'https://via.placeholder.com/400x300?text=Corsair+K95+RGB'],
            ['name' => 'SteelSeries Arctis 7', 'description' => 'Wireless gaming headset with DTS 7.1 surround', 'price' => 149.99, 'image' => 'https://via.placeholder.com/400x300?text=SteelSeries+Arctis+7'],
            ['name' => 'ASUS ROG Swift PG279Q', 'description' => '27-inch 1440p 165Hz gaming monitor', 'price' => 699.99, 'image' => 'https://via.placeholder.com/400x300?text=ASUS+ROG+Swift'],
            ['name' => 'Audioengine A2+', 'description' => 'Premium desktop speakers with built-in DAC', 'price' => 249.99, 'image' => 'https://via.placeholder.com/400x300?text=Audioengine+A2%2B'],
        ];
        
        static $index = 0;
        
        $product = $products[$index % count($products)];
        $index++;
        
        return array_merge($product, [
            'category_id' => \App\Models\Category::factory(),
        ]);
    }
}

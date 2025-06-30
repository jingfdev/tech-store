<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $categories = [
            ['name' => 'Mouse', 'description' => 'Computer mice and accessories'],
            ['name' => 'Keyboard', 'description' => 'Mechanical and membrane keyboards'],
            ['name' => 'Headset', 'description' => 'Gaming and professional headsets'],
            ['name' => 'Monitor', 'description' => 'Gaming and professional monitors'],
            ['name' => 'Speakers', 'description' => 'Desktop and portable speakers'],
        ];
        
        static $index = 0;
        
        $category = $categories[$index % count($categories)];
        $index++;
        
        return $category;
    }
}

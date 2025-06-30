<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateCategorySlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();
        
        foreach ($categories as $category) {
            $slug = strtolower(str_replace(' ', '-', $category->name));
            $category->update(['slug' => $slug]);
        }
    }
}

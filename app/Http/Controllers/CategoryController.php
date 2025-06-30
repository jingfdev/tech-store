<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display the specified category with its products.
     */
    public function show(Category $category)
    {
        // Load products for this category
        $products = $category->products()->with('category')->get();
        
        return view('categories.show', compact('category', 'products'));
    }
}

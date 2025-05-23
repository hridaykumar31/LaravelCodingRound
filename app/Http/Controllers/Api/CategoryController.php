<?php

namespace App\Http\Controllers\Api;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request) {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = Category::create($validated);
        
         return response()->json([
        'message' => 'Category added successfully',
        'category' => $category ,
    ], 201);

    }
}

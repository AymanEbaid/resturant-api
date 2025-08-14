<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Trait\apiresponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use apiresponse;

 
    public function index()
    {
        $categories = Category::all();
        return $this->success($categories, 'Categories retrieved successfully');
    }

  
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($data);

        return $this->success($category, 'Category created successfully', 201);
    }

   
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->error('Category not found', 404);
        }

        return $this->success($category, 'Category retrieved successfully');
    }

    
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        return $this->success($category, 'Category updated successfully');
    }

  
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->success(null, 'Category deleted successfully');
    }
}

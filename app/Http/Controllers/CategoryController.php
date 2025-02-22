<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function create() 
    {
        try {
        return view('category.create');
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function index()
    {
        try {
            $category = Category::all(); 
            return view('category.index', ['category' => $category]);
        } catch (\Exception $e) {
            return $e->getMessage(); 
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category,name',
            'status' => 'required|in:0,1',
            
        ]);
    
        Category::create([
            'name' => $request->name,
            'status' => $request->status,
            'user_id' => auth()->id(), 
            'store_id' => 1, 
        ]);
    
        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }
    
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.edit', compact('category'));
    }

    // Update the specified category in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    
   
}

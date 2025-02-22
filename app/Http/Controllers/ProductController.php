<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all(); 
        return view('product.create', compact('categories'));
    }

    public function index()
{
    $products = Product::with('category')->get(); // Fetch products with related categories
    return view('product.index', compact('products'));
}


public function store(Request $request)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'category_id' => 'required|exists:category,id',
        'hsn_code' => 'nullable|string|max:15',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
    ]);

    $products = new Product;

    // Handle image upload if provided
    if ($request->hasFile('product_image')) {
        $file = $request->file('product_image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('public/Image'), $filename);
        $products->product_image = $filename;
    } else {
        // Set a default value or null if no image is uploaded
        $products->product_image = null; // or set a default image path like 'default_image.jpg'
    }

    // Store other product details
    $products->product_name = $request->product_name;
    $products->category_id = $request->category_id;
    $products->hsn_code = $request->hsn_code;
    $products->description = $request->description;
    $products->user_id = Auth::user()->id; // Assuming logged-in user
    $products->store_id = 1; // You can adjust the store_id based on your logic
    $products->save();

    return redirect()->route('product.index')->with('success', 'Product created successfully!');
}


public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all();
    return view('product.edit', compact('product', 'categories'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'category_id' => 'required|exists:category,id',
        'hsn_code' => 'nullable|string|max:15',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
    ]);

    $product = Product::findOrFail($id);

    $product->product_name = $request->product_name;
    $product->category_id = $request->category_id;
    $product->hsn_code = $request->hsn_code;
    $product->description = $request->description;

    if ($request->hasFile('product_image')) {
        $file = $request->file('product_image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('public/Image'), $filename);
        $product->product_image = $filename;
    }

    $product->save();

    return redirect()->route('product.index')->with('success', 'Product updated successfully!');
}
public function destroy($id)
{
    try {
        $products = product::findOrFail($id);
        $products->delete();
        return redirect()->route('product.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('product.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}

}

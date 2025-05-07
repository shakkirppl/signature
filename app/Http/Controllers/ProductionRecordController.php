<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionRecord;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductionRecordController extends Controller
{
    public function index()
{
    $productionRecords = ProductionRecord::with('product')->latest()->get();

    return view('production-record.index', compact('productionRecords'));
}
    public function create()
    {
        $products = Product::all();
        return view('production-record.create',compact('products'));
    }

    public function store(Request $request)
{
    // return $request->all();
    $request->validate([
        'date' => 'required|date',
      'product_id' => 'required|exists:product,id',
        'processing_line' => 'nullable|string|max:255',
    ]);

   
    ProductionRecord::create([
        'date' => $request->date,
        'product_id' => $request->product_id,
        'processing_line' => $request->processing_line,
        'number_of_animals' => $request->number_of_animals ?? null,
        'user_id' => Auth::id(),
        'store_id' => 1,
    ]);

    return redirect()->route('production-record.index')
                     ->with('success', 'Production record created successfully.');
}
    
public function edit($id)
{
    $record = ProductionRecord::findOrFail($id);
    $products = Product::all(); 
    return view('production-record.edit', compact('record', 'products'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'date' => 'required|date',
        'product_id' => 'required|exists:product,id',
        'number_of_animals' => 'nullable|integer',
        'processing_line' => 'nullable|string|max:255',
    ]);

    $record = ProductionRecord::findOrFail($id);
    $record->update([
        'date' => $request->date,
        'product_id' => $request->product_id,
        'number_of_animals' => $request->number_of_animals,
        'processing_line' => $request->processing_line,
    ]);

    return redirect()->route('production-record.index')->with('success', 'Record updated successfully.');
}

public function destroy($id)
{
    $record = ProductionRecord::findOrFail($id);
    $record->delete();

    return redirect()->route('production-record.index')->with('success', 'Record deleted successfully.');
}

}

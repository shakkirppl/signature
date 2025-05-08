<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DispatchRecord;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DispatchRecordController extends Controller
{
    public function index()
{
    $dispatches = DispatchRecord::orderBy('date', 'desc')->get(); // You can also paginate: ->paginate(10)
    return view('dispatch-record.index', compact('dispatches'));
}
    public function create()
    {
        
        return view('dispatch-record.create');
    }

public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'no_of_carcasses' => 'nullable|string|max:255',
        'customer_name' => 'required|string|max:255',
        'dispatch_temperature' => 'nullable|string|max:255',
        'packaging_material_used' => 'nullable|string|max:255',
        'comments' => 'nullable|string|max:255',
    ]);

    DispatchRecord::create([
        'date' => $request->date,
        'no_of_carcasses' => $request->no_of_carcasses,
        'customer_name' => $request->customer_name,
        'dispatch_temperature' => $request->dispatch_temperature,
        'packaging_material_used' => $request->packaging_material_used,
        'comments' => $request->comments,
        'production_date' => $request->production_date,
        'expire_date' => $request->expire_date,
        'user_id' => Auth::id(),
        'store_id' => 1,
    ]);

    return redirect()->route('dispatch-record.index')->with('success', 'Dispatch Record created successfully.');
}
public function edit($id)
{
    $dispatch = DispatchRecord::findOrFail($id);
    return view('dispatch-record.edit', compact('dispatch'));
}
public function update(Request $request, $id)
{
    // Validate incoming request
    $validated = $request->validate([
        'date' => 'required|date',
        'no_of_carcasses' => 'nullable|string|max:255',
        'customer_name' => 'required|string|max:255',
        'dispatch_temperature' => 'nullable|string|max:255',
        'packaging_material_used' => 'nullable|string|max:255',
        'comments' => 'nullable|string|max:255',
    ]);

    // Find the dispatch record and update
    $dispatch = DispatchRecord::findOrFail($id);
    $dispatch->update([
        'date' => $request->date,
        'no_of_carcasses' => $request->no_of_carcasses,
        'customer_name' => $request->customer_name,
        'dispatch_temperature' => $request->dispatch_temperature,
        'packaging_material_used' => $request->packaging_material_used,
        'comments' => $request->comments,
        'production_date' => $request->production_date,
        'expire_date' => $request->expire_date,
    ]);

    // Redirect after updating
    return redirect()->route('dispatch-record.index')->with('success', 'Dispatch Record updated successfully.');
}

public function destroy($id)
{
    $dispatch = DispatchRecord::findOrFail($id);
    $dispatch->delete();

    return redirect()->route('dispatch-record.index')->with('success', 'Dispatch Record deleted successfully.');
}


}
// 
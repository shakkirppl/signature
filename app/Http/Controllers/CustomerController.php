<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use DB;

class CustomerController extends Controller
{
    public function create()
    {
        return view('customer-creation.create');
    }


    public function store(Request $request)
    {
        // Validate and save the customer data
        $validated = $request->validate([
            'customer_code' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
           
            'address' => 'required|string|max:500',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'credit_limit_days'=>  'required|numeric|min:10',
            'opening_balance' => 'nullable|numeric|min:0',
            'dr_cr' => 'required|in:Dr,Cr',

        ]);
        $validated['opening_balance'] = $request->opening_balance ?? 0;
       $validated['dr_cr'] = $request->opening_balance ? $request->dr_cr : null; 

        $validated['user_id'] = auth()->id();
        $validated['store_id'] = 1;
       
        Customer::create($validated);

        // Redirect to the index page with a success message
        return redirect()->route('customer.index')->with('success', 'Customer created successfully!');
    }


    public function index()
{
   
    $customers = Customer::all();

   
    return view('customer-creation.index', compact('customers'));
}


public function edit($id)
{
    $customer =Customer::findOrFail($id); 
    return view('customer-creation.edit', compact('customer')); 
}

public function update(Request $request, $id)
{
    $request->validate([
        'customer_code' => 'required',
        'customer_name' => 'required',
        'email' => 'nullable|email',
        'address' => 'nullable|string',
        'state' => 'nullable|string',
        'country' => 'nullable|string',
        'credit_limit_days'=>  'nullable|numeric|min:0',
    ]);

    $customer =Customer::findOrFail($id);

    
    $customer->update([
        'customer_code' => $request->customer_code,
        'customer_name' => $request->customer_name,
        'email' => $request->email,
        'address' => $request->address,
        'state' => $request->state,
        'country' => $request->country,
        'credit_limit_days'=> $request->credit_limit_days,
        'opening_balance' => $request->opening_balance ?? 0,
    ]);
    if (!empty($request->opening_balance)) {
        $updateData['dr_cr'] = $request->dr_cr;
    } else {
        $updateData['dr_cr'] = null; // Set to null if opening balance is removed
    }

    // Update the supplier record
    $customer->update($updateData);
    return redirect()->route('customer.index')->with('success');
}

public function destroy($id)
{
    try {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customer.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('customer.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}

}

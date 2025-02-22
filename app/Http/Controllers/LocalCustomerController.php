<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localcustomer;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;

class LocalCustomerController extends Controller
{
    public function create()
    {
        return view('local-customer.create',['invoice_no'=>$this->invoice_no()]);
    }
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('local_customer',Auth::user()->store_id=1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
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
        ]);

        $validated['user_id'] = auth()->id();
        $validated['store_id'] = 1;
        InvoiceNumber::updateinvoiceNumber('local_customer',1);

        Localcustomer::create($validated);

        // Redirect to the index page with a success message
        return redirect()->route('localcustomer.index')->with('success', 'Customer created successfully!');
    }


    public function index()
{
   
    $localcustomers = Localcustomer::all();

   
    return view('local-customer.index', compact('localcustomers'));
}


public function edit($id)
{
    $localcustomer =Localcustomer::findOrFail($id); 
    return view('local-customer.edit', compact('localcustomer')); 
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
    ]);

    $localcustomers =Localcustomer::findOrFail($id);

    
    $localcustomers->update([
        'customer_code' => $request->customer_code,
        'customer_name' => $request->customer_name,
        'email' => $request->email,
        'address' => $request->address,
        'state' => $request->state,
        'country' => $request->country,
    ]);

    return redirect()->route('localcustomer.index')->with('success');
}

public function destroy($id)
{
    try {
        $localcustomers = Localcustomer::findOrFail($id);
        $localcustomers->delete();
        return redirect()->route('localcustomer.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('localcustomer.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}
}

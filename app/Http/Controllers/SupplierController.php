<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;


class SupplierController extends Controller
{
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('supplier_code',Auth::user()->store_id=1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

         public function index()
        {
          $suppliers = Supplier::paginate(10);
                 
          return view('supplier.index', compact('suppliers'));
          }
                 
    public function create() 
    {
        try {
        return view('supplier.create',['invoice_no'=>$this->invoice_no()]);
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }



    public function store(Request $request)
{
    $request->validate([
       
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'contact_number' => 'required|string|max:15',
        'address' => 'nullable|string',
       'credit_limit_days'=>  'required|numeric|min:0',
       'opening_balance' => 'nullable|numeric|min:0',
       'dr_cr' => 'nullable|in:Dr,Cr',
        
    ]);

    Supplier::create([
        'code' => $request->code,
        'name' => $request->name,
        'email' => $request->email,
        'contact_number' => $request->contact_number,
        'address' => $request->address,
        'payment_terms' => $request->payment_terms,
        'credit_limit_days' => $request->credit_limit_days,
        'opening_balance' => $request->opening_balance ?? 0,
        'dr_cr' => $request->opening_balance ? $request->dr_cr : null,
        'store_id' => 1, 
       'user_id' => auth()->id(),
    ]);
    InvoiceNumber::updateinvoiceNumber('supplier_code',1);

    return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
}


public function edit($id)
{
    $supplier = Supplier::findOrFail($id);

    return view('supplier.edit', compact('supplier'));
}


public function update(Request $request, $id)
{
    // Find the supplier by ID
    $supplier = Supplier::findOrFail($id);

    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'contact_number' => 'required|string|max:15',
        'address' => 'nullable|string',
        'credit_limit_days' => 'nullable|numeric',
        'opening_balance' => 'nullable|numeric|min:0',
        'dr_cr' => 'nullable|in:Dr,Cr',
    ]);

    // Ensure dr_cr is only updated when opening_balance is present
    $updateData = [
        'name' => $request->name,
        'email' => $request->email,
        'contact_number' => $request->contact_number,
        'address' => $request->address,
        'payment_terms' => $request->payment_terms,
        'credit_limit_days' => $request->credit_limit_days,
        'opening_balance' => $request->opening_balance ?? 0, // Set to 0 if empty
    ];

    if (!empty($request->opening_balance)) {
        $updateData['dr_cr'] = $request->dr_cr;
    } else {
        $updateData['dr_cr'] = null; // Set to null if opening balance is removed
    }

    // Update the supplier record
    $supplier->update($updateData);

    // Redirect back with success message
    return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
}




}

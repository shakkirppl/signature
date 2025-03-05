<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use App\Models\OpeningBalance;



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
        DB::beginTransaction();
    
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'contact_number' => 'required|string|max:15',
                'address' => 'nullable|string',
                'credit_limit_days' => 'required|numeric|min:0',
                'opening_balance' => 'nullable|numeric|min:0',
                'dr_cr' => 'nullable|in:Dr,Cr',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
            ], [
                'name.required' => 'Please select the name.',
                'contact_number.required' => 'Please select the contact no.',
                'credit_limit_days.required' => 'Please select the credit limit days.',
               
            ]);
    
    
            $supplier = Supplier::create([
                'code' => $request->code,
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'state' => $request->state,
                'country' => $request->country,
                'payment_terms' => $request->payment_terms,
                'credit_limit_days' => $request->credit_limit_days,
                'opening_balance' => $request->opening_balance ?? 0,
                'dr_cr' => $request->opening_balance ? $request->dr_cr : null,
                'store_id' => 1,
                'user_id' => auth()->id(),
            ]);
    
            if ($request->opening_balance > 0) {
                $openingBalance = OpeningBalance::create([
                    'account_id' => $supplier->id,
                    'opening_balance' => $request->opening_balance,
                    'dr_cr' => $request->dr_cr,
                    'account_type' => 'supplier', 
                    'store_id' => 1,
                    'user_id' => auth()->id(),
                ]);
            }
    
            // Update the invoice number
            InvoiceNumber::updateinvoiceNumber('supplier_code', 1);
    
            DB::commit();
    
            return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create supplier: ' . $e->getMessage());
        }
    }


public function edit($id)
{
    $supplier = Supplier::findOrFail($id);

    return view('supplier.edit', compact('supplier'));
}


public function update(Request $request, $id) 
{
    DB::beginTransaction();

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:15',
            'address' => 'nullable|string|max:500',
            'credit_limit_days' => 'nullable|numeric|min:0',
            'opening_balance' => 'nullable|numeric|min:0',
            'dr_cr' => 'nullable|in:Dr,Cr',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);

        // Prepare update data
        $validated['opening_balance'] = $request->opening_balance ?? 0;
        $validated['dr_cr'] = $request->opening_balance ? $request->dr_cr : null;

        // Update supplier details
        $supplier->update($validated);

        // Handle Opening Balance update
        if ($request->opening_balance > 0) {
            OpeningBalance::updateOrCreate(
                ['account_id' => $supplier->id, 'account_type' => 'supplier'],
                [
                    'opening_balance' => $request->opening_balance,
                    'dr_cr' => $request->dr_cr,
                    'store_id' => 1,
                    'user_id' => auth()->id(),
                ]
            );
        } else {
            // If opening balance is removed, delete the record
            OpeningBalance::where('account_id', $supplier->id)
                ->where('account_type', 'supplier')
                ->delete();
        }

        DB::commit();

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Supplier Update Error: ' . $e->getMessage(), ['exception' => $e]);

        return redirect()->back()->with('error', 'Failed to update supplier: ' . $e->getMessage());
    }
}





}

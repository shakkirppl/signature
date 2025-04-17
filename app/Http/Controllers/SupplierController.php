<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use App\Models\OpeningBalance;
use Illuminate\Support\Facades\Validator;
use App\Models\AccountHead;



class SupplierController extends Controller
{
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('supplier_code',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

         public function index()
        {
          $suppliers = Supplier::all();
                 
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
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'contact_number' => 'required|numeric',
                'address' => 'nullable|string',
                'credit_limit_days' => 'required|numeric',
                'opening_balance' => 'nullable|numeric',
                'dr_cr' => 'nullable|in:Dr,Cr',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
            ], [
                'name.required' => 'Please select the name.',
                'contact_number.required' => 'Please select the contact no.',
                'credit_limit_days.required' => 'Please select the credit limit days.',
               
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $accountHead = AccountHead::create([
                'name' => $request->name,
                'parent_id' => '151', 
                'opening_balance' => $request->opening_balance ?? 0,
                'dr_cr' => $request->opening_balance ? $request->dr_cr : null,
                'can_delete' => 1, 
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
                'account_head_id' => $accountHead->id,
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
            \Log::error('Supplier  Error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors($validator)->withInput();
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
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'contact_number' => 'required|numeric',
                'address' => 'nullable|string',
                'credit_limit_days' => 'required|numeric',
                'opening_balance' => 'nullable|numeric',
                'dr_cr' => 'nullable|in:Dr,Cr',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
           
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

        return redirect()->back()->withErrors($validator)->withInput();
    }
}


public function destroy($id)
{
    try {
        $supplier = Supplier::findOrFail($id);
        $hasRelations =
        $supplier->purchaseConfirmations()->exists() ||
        $supplier->purchaseOrders()->exists() ||
        $supplier->outstanding()->exists(); 



    if ($hasRelations) {
        return redirect()->route('supplier.index')->with('error', 'Cannot delete supplier. It is being used in other records.');
    }
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('supplier.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}





}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\OpeningBalance;
use Illuminate\Support\Facades\DB;
use App\Models\AccountHead;


class CustomerController extends Controller
{
    public function create()
    {
        return view('customer-creation.create');
    }



    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $validated = $request->validate([
                'customer_code' => 'required|string|max:255',
                'customer_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:500',
                'state' => 'null',
                'country' => 'required|string|max:255',
                'credit_limit_days' => 'required|numeric|min:0',
                'opening_balance' => 'nullable|numeric|min:0',
                'dr_cr' => 'nullable|in:Dr,Cr',
                'account_head_id' => 'required|numeric',
            ]);
            $accountHead = AccountHead::create([
                'name' => $request->customer_name,
                'parent_id' => '151', 
                'opening_balance' => $request->opening_balance ?? 0,
                'dr_cr' => $request->opening_balance ? $request->dr_cr : null,
                'can_delete' => '1', 
            ]);
    
            $validated['opening_balance'] = $request->opening_balance ?? 0;
            $validated['dr_cr'] = $request->opening_balance ? $request->dr_cr : null;
            $validated['user_id'] = auth()->id();
            $validated['store_id'] = 1;
            $validated['account_head_id'] = $accountHead->id;

           
    
          
            $customer = Customer::create($validated);
    
            
            if ($request->opening_balance > 0) {
                OpeningBalance::create([
                    'account_id' => $customer->id, 
                    'opening_balance' => $request->opening_balance,
                    'dr_cr' => $request->dr_cr,
                    'account_type' => 'customer', 
                    'store_id' => 1,
                    'user_id' => auth()->id(),
                ]);
            }
    
            DB::commit();
    
            // Redirect to the index page with a success message
            return redirect()->route('customer.index')->with('success', 'Customer created successfully!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('customer Store Error: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
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
    DB::beginTransaction();

    try {
        $validated = $request->validate([
            'customer_code' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'state' => 'null',
            'country' => 'nullable|string|max:255',
            'credit_limit_days' => 'nullable|numeric',
            'opening_balance' => 'nullable|numeric',
            'dr_cr' =>'nullable|in:Dr,Cr', 
        ]);

        $customer = Customer::findOrFail($id);

        $validated['opening_balance'] = $request->opening_balance ?? 0;
        $validated['dr_cr'] = $request->opening_balance ? $request->dr_cr : null;

        // Update customer details
        $customer->update($validated);

        // Handle Opening Balance update
        if ($request->opening_balance > 0) {
            OpeningBalance::updateOrCreate(
                ['account_id' => $customer->id, 'account_type' => 'customer'],
                [
                    'opening_balance' => $request->opening_balance,
                    'dr_cr' => $request->dr_cr,
                    'store_id' => 1,
                    'user_id' => auth()->id(),
                ]
            );
        } else {
            // If opening balance is removed, delete the record
            OpeningBalance::where('account_id', $customer->id)
                ->where('account_type', 'customer')
                ->delete();
        }

        DB::commit();

        return redirect()->route('customer.index')->with('success', 'Customer updated successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Customer Update Error: ' . $e->getMessage(), ['exception' => $e]);

        return redirect()->back()->with('error', 'Failed to update customer: ' . $e->getMessage());
    }
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

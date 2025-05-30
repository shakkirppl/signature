<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\ExpenseVoucher;
use App\Models\BankMaster;
use App\Models\Localcustomer;
use App\Models\Shipment;
use App\Models\AccountHead;
use App\Models\AccountTransactions;

class ExpenseVoucherController extends Controller
{
//     public function index()
// {
//     $vouchers = ExpenseVoucher::with(['bank', 'account'])
//         ->whereHas('shipment', function ($query) {
//             $query->where('shipment_status', 0);
//         })
//         ->paginate(10);

//     return view('expense-voucher.index', compact('vouchers'));
// }

public function index(Request $request)
{
    $query = ExpenseVoucher::with(['bank', 'account', 'shipment'])
        ->whereHas('shipment', function ($q) {
            $q->where('shipment_status', 0);
        });

    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhereHas('account', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              })
              ->orWhere('type', 'like', "%{$search}%");
        });
    }

    $vouchers = $query->paginate(10);
    $totalAmount = $vouchers->sum('amount'); // Total on current page

    return view('expense-voucher.index', compact('vouchers', 'totalAmount'));
}

    public function create()
    {
        $banks = BankMaster::all(); 
        $localcustomers = Localcustomer::all();
        $shipments = Shipment::where('shipment_status', 0)->get(); 
        $parentIds = AccountHead::whereIn('name', ['Expenses'])->pluck('id')->toArray();
    
        // Fetch all subcategories recursively
        $coa = $this->getAllSubCategories($parentIds);


        return view('expense-voucher.create',['invoice_no'=>$this->invoice_no()],compact('banks','localcustomers','shipments','coa'));
    }

    private function getAllSubCategories($parentIds, $level = 0)
    {
        $subCategories = AccountHead::whereIn('parent_id', $parentIds)->get();
    
        if ($subCategories->isEmpty()) {
            return collect(); // No more subcategories
        }
    
        // Add indentation for hierarchical display
        foreach ($subCategories as $subCategory) {
            $subCategory->name = str_repeat('', $level) . ' ' . $subCategory->name;
        }
    
        // Recursively fetch all deeper subcategories
        return $subCategories->merge($this->getAllSubCategories($subCategories->pluck('id')->toArray(), $level + 1));
    }

    public function invoice_no(){
        try {
             
         return $invoice_no=InvoiceNumber::ReturnInvoice('expense_voucher', 1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }



                 public function store(Request $request)
                 {
                     try {
                         // Validate the request
                         $validated = $request->validate([
                            'date' => 'required|date',
                            'coa_id' => 'required|exists:account_heads,id',
                            'type' => 'required|string',
                            'amount' => 'required',
                            'description' => 'nullable|string',
                            'bank_id' => 'nullable|exists:bank_master,id',
                            'shipment_id' => 'nullable|exists:shipment,id',
                            'currency' => 'required',
                         ]);
                 
                         // Create a new payment voucher instance
                         $voucher = new ExpenseVoucher();
                         $voucher->code = $request->code; 
                         $voucher->date = $request->date;
                         $voucher->coa_id = $request->coa_id;
                         $voucher->description = $request->description;
                         $voucher->type = $request->type;
                         $voucher->amount = isset($request->amount) ? (float) str_replace(',', '', $request->amount) : 0.00;
                         $voucher->shipment_id = $request->shipment_id;
                         $voucher->user_id = Auth::id();
                         $voucher->store_id = 1;
                         $voucher->status = 1; 
                         $voucher->currency = $request->currency;

                 
                        
                         $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;
                 
                       

                         $voucher->save();
                         InvoiceNumber::updateinvoiceNumber('expense_voucher',1);
                         $group_no = AccountTransactions::orderBy('id','desc')->max('group_no');
                         $group_no+=1;
                         AccountTransactions::storeTransaction($group_no,$voucher->date,"20",$voucher->id,$voucher->coa_id,"Expense Invoice No:".$voucher->code,"Expense" ,null,$voucher->amount);
                         AccountTransactions::storeTransaction($group_no,$voucher->date,$voucher->coa_id,$voucher->id,"20","Expense Invoice No:".$voucher->code,"Expense",$voucher->amount,null);
                 
                         // Redirect with a success message
                         return redirect()->route('expensevoucher.index')->with('success', 'Expense voucher created successfully!');
                     } catch (\Exception $e) {
                         \Log::error('Expense voucher store error: ' . $e->getMessage());
                         return back()->withErrors(['error' => 'An error occurred while saving the Expense voucher.']);
                     }
                 }
                 

                 
                 public function edit($id)
                 {
                     $voucher = ExpenseVoucher::findOrFail($id);
                     $banks = BankMaster::all(); 
                     $shipments = Shipment::where('shipment_status', 0)->get();
                     $coa = AccountHead::whereIn('parent_id', function ($query) {
                        $query->select('id')
                              ->from('account_heads')
                              ->whereIn('name', ['Expenses']);
                    })->get();
                     
                     return view('expense-voucher.edit', compact('voucher', 'banks', 'shipments','coa'));
                 }
                 
                 public function update(Request $request, $id)
                 {
                     $validated = $request->validate([
                         'date' => 'required|date',
                         'coa_id' => 'required|exists:account_heads,id',
                         'type' => 'required|string|in:cash,bank',
                         'amount' => 'required|numeric',
                         'description' => 'nullable|string',
                         'bank_id' => 'nullable|exists:bank_master,id',
                         'shipment_id' => 'nullable|exists:shipment,id',
                         'currency' => 'required',
                     ]);
                 
                     $voucher = ExpenseVoucher::findOrFail($id);
                     $voucher->date = $request->date;
                     $voucher->coa_id = $request->coa_id;
                     $voucher->description = $request->description;
                     $voucher->type = $request->type;
                     $voucher->amount = $request->amount;
                     $voucher->shipment_id = $request->shipment_id;
                     $voucher->currency = $request->currency;

                   
                     
                     $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;
                     
                     $voucher->save();
                 
                     return redirect()->route('expensevoucher.index')->with('success', 'expense voucher updated successfully!');
                 }
                 
public function destroy($id)
{
    try {
        $expensevoucher = ExpenseVoucher::findOrFail($id);
        $expensevoucher->delete();
        InvoiceNumber::decreaseInvoice('expense_voucher', 1);
        return redirect()->route('expensevoucher.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('expensevoucher.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}

public function requestDelete($id)
{
    try {
        $voucher = ExpenseVoucher::findOrFail($id);
        // Check if the user is authorized
        if (Auth::user()->designation_id == 3) {
            $voucher->status = 3; // pending delete
            $voucher->save();
            return back()->with('success', 'Delete request sent for approval.');
        } else {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }
    } catch (\Exception $e) {
        \Log::error('Delete request error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong.']);
    }
}

public function pendingDeleteRequests()
{
    $vouchers = ExpenseVoucher::where('status', 3)->get();
    return view('expense-voucher.pending_delete', compact('vouchers'));
}

public function approveDelete($id)
{
    try {
        if (Auth::user()->designation_id == 1) {
            $voucher = ExpenseVoucher::findOrFail($id);
            $voucher->delete(); // Final delete
            return back()->with('success', 'Expense voucher deleted successfully.');
        }
        return back()->withErrors(['error' => 'Unauthorized action.']);
    } catch (\Exception $e) {
        \Log::error('Final delete error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong.']);
    }
}


}

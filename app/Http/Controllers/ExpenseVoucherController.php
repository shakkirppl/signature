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

class ExpenseVoucherController extends Controller
{
    public function index()
{
    $vouchers = ExpenseVoucher::with(['bank', 'account'])
        ->whereHas('shipment', function ($query) {
            $query->where('shipment_status', 0);
        })
        ->paginate(10);

    return view('expense-voucher.index', compact('vouchers'));
}

    
    



    public function create()
    {
        $banks = BankMaster::all(); 
        $localcustomers = Localcustomer::all();
        $shipments = Shipment::where('shipment_status', 0)->get(); 
        $coa = AccountHead::whereIn('parent_id', function ($query) {
            $query->select('id')
                  ->from('account_heads')
                  ->whereIn('name', ['Expenses']);
        })->get();

        return view('expense-voucher.create',['invoice_no'=>$this->invoice_no()],compact('banks','localcustomers','shipments','coa'));
    }

    public function invoice_no(){
        try {
             
         return $invoice_no=InvoiceNumber::ReturnInvoice('expense_voucher', Auth::user()->store_id = 1);
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
                            'type' => 'required|string|in:cash,bank',
                            'amount' => 'required|numeric',
                            'description' => 'nullable|string',
                            'bank_id' => 'nullable|exists:bank_master,id',
                            'shipment_id' => 'nullable|exists:shipment,id',
                         ]);
                 
                         // Create a new payment voucher instance
                         $voucher = new ExpenseVoucher();
                         $voucher->code = $request->code; 
                         $voucher->date = $request->date;
                         $voucher->coa_id = $request->coa_id;
                         $voucher->description = $request->description;
                         $voucher->type = $request->type;
                         $voucher->amount = $request->amount;
                         $voucher->shipment_id = $request->shipment_id;
                         $voucher->user_id = Auth::id();
                         $voucher->store_id = 1;
                         $voucher->status = 1; 
                 
                        
                         $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;
                 
                         InvoiceNumber::updateinvoiceNumber('expense_voucher',1);

                         $voucher->save();
                 
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
                     ]);
                 
                     $voucher = ExpenseVoucher::findOrFail($id);
                     $voucher->date = $request->date;
                     $voucher->coa_id = $request->coa_id;
                     $voucher->description = $request->description;
                     $voucher->type = $request->type;
                     $voucher->amount = $request->amount;
                     $voucher->shipment_id = $request->shipment_id;
                   
                     
                     $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;
                     
                     $voucher->save();
                 
                     return redirect()->route('expensevoucher.index')->with('success', 'expense voucher updated successfully!');
                 }
                 
public function destroy($id)
{
    try {
        $expensevoucher = ExpenseVoucher::findOrFail($id);
        $expensevoucher->delete();
        return redirect()->route('expensevoucher.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('expensevoucher.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\paymentVoucher;
use App\Models\BankMaster;
use App\Models\AccountHead;


class paymentvoucherController extends Controller
{


    public function index()
    {
        $vouchers = paymentVoucher::with('bank', 'account')->paginate(10);
        return view('paymentvoucher.index', compact('vouchers'));
    }
    



    public function create()
    {
        $banks = BankMaster::all();

        // Fetch accounts under "Expenses" and "Liabilities"
        $coa = AccountHead::whereIn('parent_id', function ($query) {
            $query->select('id')
                  ->from('account_heads')
                  ->whereIn('name', ['Expenses', 'Liabilities']);
        })->get();
        
        return view('paymentvoucher.create', [
            'invoice_no' => $this->invoice_no()
        ], compact('banks', 'coa'));
    }

    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('payment_voucher',Auth::user()->store_id=1);
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
                             'type' => 'required|string|in:cash,bank', // Ensure only valid types are allowed
                             'amount' => 'required|numeric',
                             'bank_id' => 'nullable|exists:bank_master,id', // Validate bank_id exists in bank_master
                         ]);
                 
                         // Create a new payment voucher instance
                         $voucher = new paymentVoucher();
                         $voucher->code = $request->code; // Assuming code is pre-generated
                         $voucher->date = $request->date;
                         $voucher->coa_id = $request->coa_id;
                         $voucher->description = $request->description;
                         $voucher->type = $request->type;
                         $voucher->amount = $request->amount;
                         $voucher->user_id = Auth::id();
                         $voucher->store_id = 1; // Assuming a default store ID
                         InvoiceNumber::updateinvoiceNumber('payment_voucher',1);

                         // Set the bank_id if type is 'bank'
                         $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;

                         // Save the voucher to the database
                         $voucher->save();
                 
                         return redirect()->route('paymentvoucher.index')->with('success', 'Payment voucher created successfully!');
                     } catch (\Exception $e) {
                         // Log the error and redirect back with an error message
                         \Log::error('Payment voucher store error: ' . $e->getMessage());
                         return back()->withErrors(['error' => 'An error occurred while saving the payment voucher.']);
                     }
                 }
                 

                 

                 public function edit($id)
                 {
                     $voucher = paymentVoucher::findOrFail($id);
                     $banks = BankMaster::all();
                 
                     // Fetch accounts under "Expenses" and "Liabilities"
                     $coa = AccountHead::whereIn('parent_id', function ($query) {
                         $query->select('id')
                               ->from('account_heads')
                               ->whereIn('name', ['Expenses', 'Liabilities']);
                     })->get();
                 
                     return view('paymentvoucher.edit', compact('voucher', 'banks', 'coa'));
                 }
                 

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'coa_id' => 'required|string',
        'type' => 'required|string',
        'amount' => 'required|numeric',
        'bank_name' => 'nullable|exists:bank_master,bank_name', 
    ]);

    $voucher = paymentVoucher::findOrFail($id);
    $voucher->date = $request->date;
    $voucher->coa_id = $request->coa_id;
    $voucher->description = $request->description;
    $voucher->type = $request->type;
    $voucher->amount = $request->amount;

    // If type is 'bank', store the bank_id
    if ($request->type === 'bank') {
        $bank = BankMaster::where('bank_name', $request->bank_name)->first();
        if ($bank) {
            $voucher->bank_id = $bank->id; 
        }
    } else {
        $voucher->bank_id = null; 
    }

    $voucher->save();

    return redirect()->route('paymentvoucher.index')->with('success', 'Payment voucher updated successfully!');
}

public function destroy($id)
{
    try {
        $paymentvoucher = paymentVoucher::findOrFail($id);
        $paymentvoucher->delete();
        return redirect()->route('paymentvoucher.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('paymentvoucher.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}



public function report(Request $request)
{
    $query = paymentVoucher::with('bank', 'account');

    
    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

  
    if ($request->has('type') && in_array($request->type, ['cash', 'bank'])) {
        $query->where('type', $request->type);
    }

   
    if ($request->has('bank_id') && $request->bank_id != '') {
        $query->where('bank_id', $request->bank_id);
    }
        $paymentVouchers = $query->paginate(10);

    $vouchers = $query->orderBy('date', 'desc')->get();
    $banks = BankMaster::all();

    return view('paymentvoucher.report', compact('paymentVouchers', 'banks'));
}

}

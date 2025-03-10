<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceiptVoucher;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\BankMaster;
use App\Models\AccountHead;

class ReceiptVoucherController extends Controller
{
    public function index()
    {
        $vouchers = ReceiptVoucher::with('bank','account')->paginate(10); 
        return view('receiptvoucher.index', compact('vouchers'));
    }
    
    

    public function create()
    {
        $banks = BankMaster::all();

        $coa = AccountHead::whereIn('parent_id', function ($query) {
            $query->select('id')
                  ->from('account_heads')
                  ->whereIn('name', ['Assets', 'Income']);
        })->get();
        
        return view('receiptvoucher.create', [
            'invoice_no' => $this->invoice_no()
        ], compact('banks', 'coa'));
    }



    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('receipt_voucher',1);
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
                             'coa_id' => 'required|string',
                             'type' => 'required|string|in:cash,bank', 
                             'amount' => 'required|numeric',
                             'bank_id' => 'nullable|exists:bank_master,id', 
                         ]);
                 
                         
                         $voucher = new ReceiptVoucher();
                         $voucher->code = $request->code; 
                         $voucher->date = $request->date;
                         $voucher->coa_id = $request->coa_id;
                         $voucher->description = $request->description;
                         $voucher->type = $request->type;
                         $voucher->amount = $request->amount;
                         $voucher->user_id = Auth::id();
                         $voucher->store_id = 1; 
                 
                         $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;
                        InvoiceNumber::updateinvoiceNumber('receipt_voucher',1);

                         $voucher->save();
                 
                         return redirect()->route('receiptvoucher.index')->with('success');
                     } catch (\Exception $e) {
                         \Log::error('Payment voucher store error: ' . $e->getMessage());
                         return back()->withErrors(['error' => 'An error occurred while saving the receipt voucher.']);
                     }
                 }
                 

                 


    public function edit($id)
    {
        $voucher = ReceiptVoucher::findOrFail($id);
        $banks = BankMaster::all();
    
        $coa = AccountHead::whereIn('parent_id', function ($query) {
            $query->select('id')
                  ->from('account_heads')
                  ->whereIn('name', ['Assets', 'Income']);
        })->get();
    
        return view('receiptvoucher.edit', compact('voucher', 'banks', 'coa'));
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

    $voucher = ReceiptVoucher::findOrFail($id);
    $voucher->date = $request->date;
    $voucher->coa_id = $request->coa_id;
    $voucher->description = $request->description;
    $voucher->type = $request->type;
    $voucher->amount = $request->amount;

    if ($request->type === 'bank') {
        $bank = BankMaster::where('bank_name', $request->bank_name)->first();
        if ($bank) {
            $voucher->bank_id = $bank->id; 
        }
    } else {
        $voucher->bank_id = null; 
    }

    $voucher->save();

    return redirect()->route('receiptvoucher.index')->with('success', 'Payment voucher updated successfully!');
}

public function destroy($id)
{
    try {
        $receiptvoucher = ReceiptVoucher::findOrFail($id);
        $receiptvoucher->delete();
        return redirect()->route('receiptvoucher.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('receiptvoucher.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}


public function report(Request $request)
{
    $query = ReceiptVoucher::with('bank', 'account');

    
    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

  
    if ($request->has('type') && in_array($request->type, ['cash', 'bank'])) {
        $query->where('type', $request->type);
    }

   
    if ($request->has('bank_id') && $request->bank_id != '') {
        $query->where('bank_id', $request->bank_id);
    }
        $receiptVouchers = $query->paginate(10);

    $vouchers = $query->orderBy('date', 'desc')->get();
    $banks = BankMaster::all();

    return view('receiptvoucher.report', compact('receiptVouchers', 'banks'));
}

}

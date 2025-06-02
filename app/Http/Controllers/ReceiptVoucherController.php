<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceiptVoucher;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\BankMaster;
use App\Models\AccountHead;
use App\Models\AccountTransactions;

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
                             'type' => 'required|string', 
                             'amount' => 'required',
                             'bank_id' => 'nullable|exists:bank_master,id', 
                             'currency' => 'required',
                         ]);
                 
                         
                         $voucher = new ReceiptVoucher();
                         $voucher->code = $request->code; 
                         $voucher->date = $request->date;
                         $voucher->coa_id = $request->coa_id;
                         $voucher->description = $request->description;
                         $voucher->type = $request->type;
                         $voucher->amount = isset($request->amount) ? (float) str_replace(',', '', $request->amount) : 0.00;
                         $voucher->user_id = Auth::id();
                         $voucher->store_id = 1; 
                         $voucher->currency = $request->currency; 
                         $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;
                       

                         $voucher->save();
                         InvoiceNumber::updateinvoiceNumber('receipt_voucher',1);
                         $group_no = AccountTransactions::orderBy('id','desc')->max('group_no');
                         $group_no+=1;
                         AccountTransactions::storeTransaction($group_no,$voucher->date,"20",$voucher->id,$voucher->coa_id,"Receipt Invoice No:".$voucher->code,"Recipt",$voucher->amount ,null);
                         AccountTransactions::storeTransaction($group_no,$voucher->date,$voucher->coa_id,$voucher->id,"20","Receipt Invoice No:".$voucher->code,"Receipt",null,$voucher->amount);

                 
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
        'currency' => 'required',
    ]);

    $voucher = ReceiptVoucher::findOrFail($id);
    $voucher->date = $request->date;
    $voucher->coa_id = $request->coa_id;
    $voucher->description = $request->description;
    $voucher->type = $request->type;
    $voucher->currency = $request->currency;
    $voucher->amount = $request->amount;

    $voucher->bank_id = ($request->type === 'bank') ? $request->bank_id : null;


    $voucher->save();

    return redirect()->route('receiptvoucher.index')->with('success', 'Payment voucher updated successfully!');
}

public function destroy($id)
{
    try {
        $receiptvoucher = ReceiptVoucher::findOrFail($id);
        $receiptvoucher->delete();
        InvoiceNumber::decreaseInvoice('receipt_voucher', 1);
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

public function requestDelete($id)
{
    $voucher = ReceiptVoucher::findOrFail($id);

    if (Auth::user()->designation_id == 3) {
        $voucher->delete_status = 1;
        $voucher->save();
        return redirect()->back()->with('success', 'Delete request sent to admin.');
    }

    return redirect()->back()->withErrors('Unauthorized action.');
}

public function deleteRequests()
{
    if (Auth::user()->designation_id != 1) {
        abort(403);
    }

    $vouchers = ReceiptVoucher::where('delete_status', 1)->get();
    return view('receiptvoucher.pending-delete', compact('vouchers'));
}

public function confirmDelete($id)
{
    $voucher = ReceiptVoucher::findOrFail($id);

    if (Auth::user()->designation_id == 1 && $voucher->delete_status == 1) {
        $voucher->delete(); // Or forceDelete if you're using soft deletes
        return redirect()->back()->with('success', 'Voucher permanently deleted.');
    }

    return redirect()->back()->withErrors('Unauthorized or invalid action.');
}

public function editRequest($id)
{
    $voucher = ReceiptVoucher::findOrFail($id);
    $banks = BankMaster::all();

    $coa = AccountHead::whereIn('parent_id', function ($query) {
        $query->select('id')
              ->from('account_heads')
              ->whereIn('name', ['Assets', 'Income']);
    })->get();

    return view('receiptvoucher.edit-request', compact('voucher', 'banks', 'coa'));
}

public function sendEditRequest(Request $request, $id)
{
    $voucher = ReceiptVoucher::findOrFail($id);

    $validated = $request->validate([
        'date' => 'required|date',
        'coa_id' => 'required|string',
        'type' => 'required|string',
        'amount' => 'required|numeric',
        'description' => 'nullable|string',
        'bank_id' => 'nullable|exists:bank_master,id',
        'currency' => 'required|string',
    ]);

    $voucher->edit_request_data = json_encode([
        'date' => $request->date,
        'coa_id' => $request->coa_id,
        'type' => $request->type,
        'amount' => (float) str_replace(',', '', $request->amount),
        'description' => $request->description,
        'bank_id' => $request->type === 'bank' ? $request->bank_id : null,
        'currency' => $request->currency,
    ]);

    $voucher->edit_status = 'pending';
    $voucher->save();

    return redirect()->route('receiptvoucher.index')->with('success', 'Edit request sent and awaiting approval.');
}

public function pendingEditRequests()
{
    $pendingVouchers = ReceiptVoucher::where('edit_status', 'pending')->get();
    return view('receiptvoucher.pending-edit-request', compact('pendingVouchers'));
}


public function approveEditRequest($id)
{
    $voucher = ReceiptVoucher::findOrFail($id);

    if ($voucher->edit_status !== 'pending') {
        return back()->withErrors('No pending edit request found.');
    }

    $data = json_decode($voucher->edit_request_data, true);

    if (is_array($data)) {
        foreach ($data as $field => $value) {
            $voucher->$field = $value;
        }
    } else {
        return back()->withErrors('Invalid edit request data.');
    }

    $voucher->edit_status = 'approved';
    $voucher->edit_request_data = null;
    $voucher->save();

    return redirect()->back()->with('success', 'Edit request approved and applied.');
}


public function rejectEditRequest($id)
{
    $voucher = ReceiptVoucher::findOrFail($id);

    if ($voucher->edit_status !== 'pending') {
        return back()->withErrors('No pending edit request found.');
    }

    $voucher->edit_status = 'rejected';
    $voucher->edit_request_data = null;
    $voucher->save();

    return redirect()->back()->with('success', 'Edit request rejected.');
}



}

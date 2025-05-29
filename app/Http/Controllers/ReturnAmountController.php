<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Supplier;
use App\Models\Outstanding;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;
use App\Models\PurchaseConformation;
use App\Models\ReturnAmount;
use Carbon\Carbon;
class ReturnAmountController extends Controller
{
    public function create()
    {
        $shipments = Shipment::where('shipment_status', 0)->get();
        return view('return-amount.create', compact('shipments'));
    }

    // Fetch suppliers for a shipment
    public function getSuppliersByShipment(Request $request)
    {
        $shipmentId = $request->shipment_id;

        $suppliers = PurchaseOrder::where('shipment_id', $shipmentId)
            ->with('supplier')
            ->select('supplier_id')
            ->distinct()
            ->get();

        $supplierData = [];
        foreach ($suppliers as $supplier) {
            if ($supplier->supplier) {
                $supplierData[] = [
                    'id' => $supplier->supplier_id,
                    'name' => $supplier->supplier->name,
                ];
            }
        }

        return response()->json(['suppliers' => $supplierData]);
    }

    // Fetch outstanding balance for a supplier
    public function getSupplierOutstanding(Request $request)
    {
        $supplierId = $request->supplier_id;

        $outstanding = Outstanding::where('account_type', 'supplier')
            ->where('account_id', $supplierId)
            ->select(DB::raw('SUM(payment) as total_payment'), DB::raw('SUM(receipt) as total_receipt'))
            ->first();

        $balance = $outstanding ? $outstanding->total_payment - $outstanding->total_receipt : 0;

        return response()->json(['balance' => number_format($balance, 2, '.', '')]);
    }
    public function getAllSuppliers()
    {
        $suppliers = Supplier::select('id', 'name')->get();
        return response()->json(['suppliers' => $suppliers]);
    }
//     public function store(Request $request)
//     {
//         // return $request->all();
//         $request->validate([
//             'date' => 'required|date',
//             'shipment_id' => 'nullable|exists:shipment,id',
//             'supplier_id' => 'required|exists:supplier,id',
//             'retrun_amount' => 'required|numeric|min:0.01',
//         ]);
    
//         DB::beginTransaction();
    
//         try {
           
    
//             // Step 1: Insert into return_payments table
//             $returnPayment = ReturnAmount::create([
//                 'date' => $request->date,
//                 'type' => $request->type,
//                 'supplier_id' => $request->supplier_id,
//                 'retrun_amount' => $request->retrun_amount,
//                 'store_id' => 1,
//                  'user_id' => auth()->id(),
//                  'shipment_id' => $request->type === 'transaction' ? $request->shipment_id : null,

//             ]);
    
//             // Step 2: Insert into outstandings table
//            // Step 2: Insert into outstandings table
// Outstanding::create([
//     'date' => $request->date,
//     'time' => Carbon::now()->format('H:i:s'),
//     'account_id' => $request->supplier_id,
//     'receipt' => $request->type === 'return_supplier' ? -abs($request->retrun_amount) : null,
//     'payment' => in_array($request->type, ['opening_balance', 'transaction']) ? -abs($request->retrun_amount) : null,
//     'narration' => match ($request->type) {
//         'opening_balance' => 'Return payment (Opening Balance)',
//         'return_supplier' => 'Supplier return (against received amount)',
//         default => 'Return payment from supplier',
//     },
//     'transaction_id' => $returnPayment->id,
//     'transaction_type' => 'Return Payment',
//     'description' => 'Return Payment - ' . match ($request->type) {
//         'opening_balance' => 'Opening Balance',
//         'transaction' => 'Shipment ID: ' . $request->shipment_id,
//         'return_supplier' => 'Return Supplier',
//         default => '',
//     },
//     'account_type' => 'supplier',
//     'store_id' => $returnPayment->store_id,
//     'user_id' => $returnPayment->user_id,
//     'financial_year' => date('Y'),
// ]);

    
//             DB::commit();
    
//             return redirect()->route('return-payment.index')->with('success', 'Return payment saved successfully.');
    
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return back()->with('error', 'Failed to save return payment: ' . $e->getMessage());
//         }
//     }

 public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'date' => 'required|date',
            'shipment_id' => 'nullable|exists:shipment,id',
            'supplier_id' => 'required|exists:supplier,id',
            'retrun_amount' => 'required|numeric|min:0.01',
        ]);
    
        DB::beginTransaction();
    
        try {
           
    
            // Step 1: Insert into return_payments table
            $returnPayment = ReturnAmount::create([
                'date' => $request->date,
                'type' => $request->type,
                'supplier_id' => $request->supplier_id,
                'retrun_amount' => $request->retrun_amount,
                'store_id' => 1,
                 'user_id' => auth()->id(),
                 'shipment_id' => $request->type === 'transaction' ? $request->shipment_id : null,

            ]);
    
            // Step 2: Insert into outstandings table
            Outstanding::create([
                'date' => $request->date,
                'time' => Carbon::now()->format('H:i:s'),
                'account_id' => $request->supplier_id,
                'receipt' => null,
                'payment' => -abs($request->retrun_amount),
                'narration' => $request->type === 'opening_balance' ? 'Return payment (Opening Balance)' : 'Return payment from supplier',
                'transaction_id' => $returnPayment->id,
                'transaction_type' => 'Return Payment',
                'description' => 'Return Payment - ' . ($request->type === 'transaction' ? 'Shipment ID: ' . $request->shipment_id : 'Opening Balance'),
                'account_type' => 'supplier',
                'store_id' => $returnPayment->store_id,
                'user_id' => $returnPayment->user_id,
                'financial_year' => date('Y'),
            ]);
            
    
            DB::commit();
    
            return redirect()->route('return-payment.index')->with('success', 'Return payment saved successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save return payment: ' . $e->getMessage());
        }
    }


    public function index()
{
    $returnPayments = ReturnAmount::with(['supplier', 'shipment'])->orderBy('id')->get();
    return view('return-amount.index', compact('returnPayments'));
}

public function destroy($id)
{
    DB::beginTransaction();

    try {
        $returnPayment = ReturnAmount::findOrFail($id);

        // Delete the related outstanding entry
        Outstanding::where('transaction_type', 'Return Payment')
            ->where('transaction_id', $returnPayment->id)
            ->delete();

        // Delete the return payment record
        $returnPayment->delete();

        DB::commit();
        return redirect()->route('return-payment.index')->with('success', 'Return payment deleted successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error deleting return payment: ' . $e->getMessage());
    }
}

public function requestDelete($id)
{
    $payment = ReturnAmount::findOrFail($id);

    // Only allow designation_id == 3 to request delete
    if (auth()->user()->designation_id == 3) {
        $payment->status = 'pending_delete';
        $payment->save();

        return redirect()->back()->with('success', 'Delete request sent successfully.');
    }

    return back()->with('error', 'Unauthorized action.');
}


public function pendingDeleteList()
{
    $pendingDeletes = ReturnAmount::with(['supplier', 'shipment', 'user'])
        ->where('status', 'pending_delete')
        ->get();

    return view('return-amount.pending-delete', compact('pendingDeletes'));
}


public function approveDelete($id)
{
    DB::beginTransaction();

    try {
        $payment = ReturnAmount::findOrFail($id);

        // Delete related outstanding
        Outstanding::where('transaction_type', 'Return Payment')
            ->where('transaction_id', $payment->id)
            ->delete();

        $payment->delete(); // Hard delete

        DB::commit();
        return redirect()->back()->with('success', 'Return payment permanently deleted.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error deleting return payment.');
    }
}




}








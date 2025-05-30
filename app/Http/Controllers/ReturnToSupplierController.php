<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnToSupplier;
use App\Models\Outstanding;
use App\Models\Shipment;
use App\Models\Supplier;
use Carbon\Carbon;
use DB;

class ReturnToSupplierController extends Controller
{
    public function create()
    {
        $shipments = Shipment::where('shipment_status', 0)->get();
        return view('return-to-supplier.create', compact('shipments'));
    }

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
            $return = ReturnToSupplier::create([
                'date' => $request->date,
                'type' => 'return_supplier',
                'supplier_id' => $request->supplier_id,
                'retrun_amount' => $request->retrun_amount,
                'store_id' => 1,
                'user_id' => auth()->id(),
                'shipment_id' => $request->shipment_id,
            ]);

            Outstanding::create([
                'date' => $request->date,
                'time' => Carbon::now()->format('H:i:s'),
                'account_id' => $request->supplier_id,
                'receipt' => -abs($request->retrun_amount),
                'payment' => null,
                'narration' => 'Supplier return (against received amount)',
                'transaction_id' => $return->id,
                'transaction_type' => 'Return To Supplier',
                'description' => 'Return to Supplier',
                'account_type' => 'supplier',
                'store_id' => $return->store_id,
                'user_id' => $return->user_id,
                'financial_year' => date('Y'),
            ]);

            DB::commit();
            return redirect()->route('return-to-supplier.index')->with('success', 'Return to Supplier recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $returns = ReturnToSupplier::with(['supplier', 'shipment'])->get();
        return view('return-to-supplier.index', compact('returns'));
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
    $outstandings = Outstanding::select(
        'account_id',
        DB::raw('SUM(payment) as total_payment'),
        DB::raw('SUM(receipt) as total_receipt')
    )
    ->where('account_type', 'supplier')
    ->groupBy('account_id')
    ->get()
    ->filter(function ($item) {
        // Only include suppliers who owe you money (green color)
        return $item->total_receipt > $item->total_payment;
    });

    $supplierIds = $outstandings->pluck('account_id')->toArray();

    $suppliers = Supplier::whereIn('id', $supplierIds)->select('id', 'name')->get();

    return response()->json(['suppliers' => $suppliers]);
}

public function destroy($id)
{
    $return = ReturnToSupplier::findOrFail($id);

    DB::beginTransaction();
    try {
        // Restore the deducted amount back to Outstanding
        Outstanding::create([
            'date' => Carbon::now()->format('Y-m-d'),
            'time' => Carbon::now()->format('H:i:s'),
            'account_id' => $return->supplier_id,
            'receipt' => abs($return->retrun_amount), // restore it as positive
            'payment' => null,
            'narration' => 'Restore after deleting Return To Supplier',
            'transaction_id' => $return->id,
            'transaction_type' => 'Return To Supplier Delete',
            'description' => 'Reversal of return to supplier',
            'account_type' => 'supplier',
            'store_id' => $return->store_id,
            'user_id' => auth()->id(),
            'financial_year' => date('Y'),
        ]);

        // Delete the return entry
        $return->delete();

        DB::commit();
        return redirect()->route('return-to-supplier.index')->with('success', 'Return entry deleted and balance restored.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function requestDelete($id)
{
    $return = ReturnToSupplier::findOrFail($id);

    // Only users with designation_id == 3 can request delete
    if (auth()->user()->designation_id != 3) {
        return back()->with('error', 'Unauthorized action.');
    }

    $return->update(['delete_status' => true]);

    return back()->with('success', 'Delete request submitted.');
}

public function pendingDeletes()
{
    if (auth()->user()->designation_id != 1) {
        abort(403);
    }

    $returns = ReturnToSupplier::where('delete_status', true)->with('supplier')->get();

    return view('return-to-supplier.pending-delete', compact('returns'));
}

public function approvedestroy($id)
{
    $return = ReturnToSupplier::findOrFail($id);

    if (auth()->user()->designation_id != 1) {
        return back()->with('error', 'Only admins can delete.');
    }

    DB::beginTransaction();
    try {
        Outstanding::create([
            'date' => Carbon::now()->format('Y-m-d'),
            'time' => Carbon::now()->format('H:i:s'),
            'account_id' => $return->supplier_id,
            'receipt' => abs($return->retrun_amount),
            'payment' => null,
            'narration' => 'Restore after deleting Return To Supplier',
            'transaction_id' => $return->id,
            'transaction_type' => 'Return To Supplier Delete',
            'description' => 'Reversal of return to supplier',
            'account_type' => 'supplier',
            'store_id' => $return->store_id,
            'user_id' => auth()->id(),
            'financial_year' => date('Y'),
        ]);

        $return->delete();

        DB::commit();
        return redirect()->route('return-to-supplier.pending-delete')->with('success', 'Entry deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}




}


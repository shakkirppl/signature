<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outstanding;
use App\Models\Supplier;
use App\Models\Customer;
class OutstandingController extends Controller
{
    
    public function supplierLedger(Request $request)
    {
        $outstandings = collect(); // Empty collection by default
        $suppliers = Supplier::all();
    
        if ($request->filled('from_date') && $request->filled('to_date') && $request->filled('supplier_id')) {
            $outstandings = Outstanding::where('account_type', 'supplier')
                ->whereBetween('date', [$request->from_date, $request->to_date])
                ->where('account_id', $request->supplier_id) // Filtering by account_id since it stores supplier ID
                ->get();
        }
    
        return view('supplier-ledger.index', compact('outstandings', 'suppliers'));
    }
    
    

    

    public function customerLedger(Request $request)
    {
        $outstandings = collect(); // Empty collection by default
        $customers = Customer::all();
    
        if ($request->filled('from_date') && $request->filled('to_date') && $request->filled('customer_id')) {
            $outstandings = Outstanding::where('account_type', 'customer')
                ->whereBetween('date', [$request->from_date, $request->to_date])
                ->where('account_id', $request->customer_id)
                ->where(function ($query) {
                    $query->where('receipt', '>', 0)
                          ->orWhere('payment', '>', 0);
                }) // Ensuring only records where receipt or payment > 0 are included
                ->orderBy('in_date', 'ASC')
                ->get();
        }
    
        return view('customer-ledger.index', compact('outstandings', 'customers'));
    }
    


      
    public function customerindex()
    {
        $outstandings = Outstanding::where('account_type', 'customer')->get();
        return view('customer-outstanding.index', compact('outstandings')); 
    }


    public function supplierOutstanding()
{
    $outstandings = Outstanding::select(
            'account_id',
            \DB::raw('SUM(payment) as total_payment'),
            \DB::raw('SUM(receipt) as total_receipt')
        )
        ->where('account_type', 'supplier') // Only suppliers
        ->groupBy('account_id') // Group by supplier
        ->get();

    // Fetch supplier names
    $suppliers = Supplier::whereIn('id', $outstandings->pluck('account_id'))->pluck('name', 'id');

    return view('supplier_outstanding.index', compact('outstandings', 'suppliers'));
}


public function customerOutstanding()
{
    $outstandings = Outstanding::select(
            'account_id',
            \DB::raw('SUM(payment) as total_payment'),
            \DB::raw('SUM(receipt) as total_receipt')
        )
        ->where('account_type', 'customer') // Only customers
        ->groupBy('account_id') // Group by customer
        ->get();

    // Fetch customer names
    $customers = Customer::whereIn('id', $outstandings->pluck('account_id'))->pluck('customer_name', 'id');

    return view('customer_outstanding.index', compact('outstandings', 'customers'));
}

    
}

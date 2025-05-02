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
                ->where(function ($query) {
                    $query->where('receipt', '>', 0)
                          ->orWhere('payment', '>', 0);
                })
                ->orderBy('date', 'ASC')
                ->get();

                $totalPayment = $outstandings->sum('payment');
                $totalReceipt = $outstandings->sum('receipt');
        
                // If payment > receipt → closing under payment
                // If receipt > payment → closing under receipt
                if ($totalPayment > $totalReceipt) {
                    $closingAmount = $totalPayment - $totalReceipt;
                } elseif ($totalReceipt > $totalPayment) {
                    $closingAmount = $totalReceipt - $totalPayment;
                }
        }
    
        return view('supplier-ledger.index', compact('outstandings', 'suppliers'));
    }
    
    

    

    public function customerLedger(Request $request)
    {
        $outstandings = collect(); // Empty collection by default
        $customers = Customer::all();
        $closingAmount = 0;
    
        if ($request->filled('from_date') && $request->filled('to_date') && $request->filled('customer_id')) {
            $outstandings = Outstanding::where('account_type', 'customer')
                ->whereBetween('date', [$request->from_date, $request->to_date])
                ->where('account_id', $request->customer_id)
                ->where(function ($query) {
                    $query->where('receipt', '>', 0)
                          ->orWhere('payment', '>', 0);
                })
                ->orderBy('date', 'ASC')
                ->get();
    
            // Calculate the closing balance
            $totalPayment = $outstandings->sum('payment');
            $totalReceipt = $outstandings->sum('receipt');
    
            // If payment > receipt → closing under payment
            // If receipt > payment → closing under receipt
            if ($totalPayment > $totalReceipt) {
                $closingAmount = $totalPayment - $totalReceipt;
            } elseif ($totalReceipt > $totalPayment) {
                $closingAmount = $totalReceipt - $totalPayment;
            }
        }
    
        return view('customer-ledger.index', compact('outstandings', 'customers', 'closingAmount'));
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
    foreach ($outstandings as $outstanding) {
        if ($outstanding->total_payment > $outstanding->total_receipt) {
            $outstanding->outstanding_balance = $outstanding->total_payment - $outstanding->total_receipt;
        } else {
            $outstanding->outstanding_balance = $outstanding->total_receipt - $outstanding->total_payment;
        }
    }

    return view('supplier_outstanding.index', compact('outstandings', 'suppliers'));
}


public function customerOutstanding()
{
    $outstandings = Outstanding::select(
            'account_id',
            \DB::raw('SUM(payment) as total_payment'),
            \DB::raw('SUM(receipt) as total_receipt')
        )
        ->where('account_type', 'customer')
        ->groupBy('account_id')
        ->get();

    $totalNegative = 0;
    $customers = Customer::whereIn('id', $outstandings->pluck('account_id'))->pluck('customer_name', 'id');

    foreach ($outstandings as $outstanding) {
        if ($outstanding->total_payment > $outstanding->total_receipt) {
            $outstanding->outstanding_balance = $outstanding->total_payment - $outstanding->total_receipt;
            $totalNegative += $outstanding->outstanding_balance;
        } else {
            $outstanding->outstanding_balance = $outstanding->total_receipt - $outstanding->total_payment;
        }
    }

    return view('customer_outstanding.index', compact('outstandings', 'customers', 'totalNegative'));
}
public function customerOutstandingPrint()
{
    $outstandings = Outstanding::select(
            'account_id',
            \DB::raw('SUM(payment) as total_payment'),
            \DB::raw('SUM(receipt) as total_receipt')
        )
        ->where('account_type', 'customer')
        ->groupBy('account_id')
        ->get();

    $totalNegative = 0;
    $customers = Customer::whereIn('id', $outstandings->pluck('account_id'))->pluck('customer_name', 'id');

    foreach ($outstandings as $outstanding) {
        if ($outstanding->total_payment > $outstanding->total_receipt) {
            $outstanding->outstanding_balance = $outstanding->total_payment - $outstanding->total_receipt;
            $totalNegative += $outstanding->outstanding_balance;
        } else {
            $outstanding->outstanding_balance = $outstanding->total_receipt - $outstanding->total_payment;
        }
    }

    return view('customer_outstanding.print', compact('outstandings', 'customers', 'totalNegative'));
}



public function supplierOutstandingPrint()
{
    $outstandings = Outstanding::select(
            'account_id',
            \DB::raw('SUM(payment) as total_payment'),
            \DB::raw('SUM(receipt) as total_receipt')
        )
        ->where('account_type', 'supplier')
        ->groupBy('account_id')
        ->get();

    $suppliers = Supplier::whereIn('id', $outstandings->pluck('account_id'))->pluck('name', 'id');

    foreach ($outstandings as $outstanding) {
        if ($outstanding->total_payment > $outstanding->total_receipt) {
            $outstanding->outstanding_balance = $outstanding->total_payment - $outstanding->total_receipt;
        } else {
            $outstanding->outstanding_balance = $outstanding->total_receipt - $outstanding->total_payment;
        }
    }

    return view('supplier_outstanding.print', compact('outstandings', 'suppliers'));
}


    
}

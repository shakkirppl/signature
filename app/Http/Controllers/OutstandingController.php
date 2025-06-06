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


//     public function supplierOutstanding()
// {
//     $outstandings = Outstanding::select(
//             'account_id',
//             \DB::raw('SUM(payment) as total_payment'),
//             \DB::raw('SUM(receipt) as total_receipt')
//         )
//         ->where('account_type', 'supplier') // Only suppliers
//         ->groupBy('account_id') // Group by supplier
//         ->get();

//     // Fetch supplier names
//     $suppliers = Supplier::whereIn('id', $outstandings->pluck('account_id'))->pluck('name', 'id');
//     foreach ($outstandings as $outstanding) {
//         if ($outstanding->total_payment > $outstanding->total_receipt) {
//             $outstanding->outstanding_balance = $outstanding->total_payment - $outstanding->total_receipt;
//         } else {
//             $outstanding->outstanding_balance = $outstanding->total_receipt - $outstanding->total_payment;
//         }
//     }

//     return view('supplier_outstanding.index', compact('outstandings', 'suppliers'));
// }
public function supplierOutstanding()
{
    $accountType = 'supplier';
    $outstandings = Outstanding::where('account_type', $accountType)
        ->orderBy('date')
        ->get()
        ->groupBy('account_id');

    $finalOutstandings = [];
    $suppliers = Supplier::whereIn('id', $outstandings->keys())->pluck('name', 'id');

    foreach ($outstandings as $accountId => $transactions) {
        $totalPayment = 0;
        $totalReceipt = 0;
        $outstandingBalance = 0;
        $lastZeroBalanceDate = null;

        foreach ($transactions as $transaction) {
            $totalPayment += $transaction->payment;
            $totalReceipt += $transaction->receipt;

            $currentBalance = $totalPayment - $totalReceipt;

            if (round($currentBalance, 2) == 0) {
                $lastZeroBalanceDate = $transaction->date;
            }
        }

        $outstandingBalance = abs($totalPayment - $totalReceipt);

        if ($outstandingBalance == 0) {
            continue; // Skip if no outstanding
        }

        // Use last zero balance date or first transaction if never settled
        $baseDate = $lastZeroBalanceDate ?? $transactions->first()->date;
        $daysSince = \Carbon\Carbon::parse($baseDate)->diffInDays(now());

        $finalOutstandings[] = (object)[
            'account_id' => $accountId,
            'supplier_name' => $suppliers[$accountId] ?? 'Unknown Supplier',
            'total_payment' => $totalPayment,
            'total_receipt' => $totalReceipt,
            'outstanding_balance' => $outstandingBalance,
            'is_receivable' => $totalPayment > $totalReceipt,
            'days_since_outstanding_started' => $daysSince,
        ];
    }

    return view('supplier_outstanding.index', [
        'outstandings' => collect($finalOutstandings),
        'suppliers' => $suppliers,
    ]);
}



public function customerOutstanding()
{
    $accountType = 'customer';

    // Fetch all customer transactions sorted by date
    $grouped = Outstanding::where('account_type', $accountType)
        ->orderBy('date')
        ->get()
        ->groupBy('account_id');

    $finalOutstandings = [];
    $customers = Customer::whereIn('id', $grouped->keys())->pluck('customer_name', 'id');

    $totalNegative = 0;

    foreach ($grouped as $accountId => $transactions) {
        $totalPayment = 0;
        $totalReceipt = 0;
        $outstandingBalance = 0;
        $lastZeroBalanceDate = null;

        foreach ($transactions as $transaction) {
            $totalPayment += $transaction->payment;
            $totalReceipt += $transaction->receipt;

            $currentBalance = $totalReceipt - $totalPayment;

            if (round($currentBalance, 2) == 0) {
                $lastZeroBalanceDate = $transaction->date;
            }
        }

        $outstandingBalance = abs($totalPayment - $totalReceipt);

        if ($outstandingBalance == 0) {
            continue; // Skip if no outstanding balance
        }

        $isReceivable = $totalReceipt > $totalPayment;

        $baseDate = $lastZeroBalanceDate ?? $transactions->first()->date;
        $daysSinceOutstanding = \Carbon\Carbon::parse($baseDate)->diffInDays(now());

        if (!$isReceivable) {
            $totalNegative += $outstandingBalance;
        }

        $finalOutstandings[] = (object)[
            'account_id' => $accountId,
            'customer_name' => $customers[$accountId] ?? 'Unknown Customer',
            'total_payment' => $totalPayment,
            'total_receipt' => $totalReceipt,
            'outstanding_balance' => $outstandingBalance,
            'is_receivable' => $isReceivable,
            'days_since_outstanding_started' => $daysSinceOutstanding,
        ];
    }

    return view('customer_outstanding.index', [
        'outstandings' => collect($finalOutstandings),
        'customers' => $customers,
        'totalNegative' => $totalNegative,
    ]);
}



// public function customerOutstanding()
// {
//     $outstandings = Outstanding::select(
//             'account_id',
//             \DB::raw('SUM(payment) as total_payment'),
//             \DB::raw('SUM(receipt) as total_receipt')
//         )
//         ->where('account_type', 'customer')
//         ->groupBy('account_id')
//         ->get();

//     $totalNegative = 0;
//     $customers = Customer::whereIn('id', $outstandings->pluck('account_id'))->pluck('customer_name', 'id');

//     foreach ($outstandings as $outstanding) {
//         if ($outstanding->total_payment > $outstanding->total_receipt) {
//             $outstanding->outstanding_balance = $outstanding->total_payment - $outstanding->total_receipt;
//             $totalNegative += $outstanding->outstanding_balance;
//         } else {
//             $outstanding->outstanding_balance = $outstanding->total_receipt - $outstanding->total_payment;
//         }
//     }

//     return view('customer_outstanding.index', compact('outstandings', 'customers', 'totalNegative'));
// }

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

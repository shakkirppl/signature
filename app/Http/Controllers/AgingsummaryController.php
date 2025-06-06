<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outstanding;
use App\Models\Customer;
use App\Models\Supplier;
class AgingsummaryController extends Controller
{
public function agingSummary()
{
    $customerSummary = $this->calculateAging('customer');
    $supplierSummary = $this->calculateAging('supplier');

    return view('aging-report.index', [
        'customerSummary' => $customerSummary,
        'supplierSummary' => $supplierSummary
    ]);
}

public function agingDetailed()
{
    $transactions = Outstanding::with(['customer', 'supplier'])
        ->select('*', \DB::raw('DATEDIFF(NOW(), date) as days_old'))
        ->orderBy('date', 'asc')
        ->get()
        ->map(function ($transaction) {
            $transaction->amount = $transaction->receipt - $transaction->payment;
            $transaction->absolute_amount = abs($transaction->amount);
            
            if ($transaction->days_old <= 30) {
                $transaction->aging_bucket = '0-30';
            } elseif ($transaction->days_old <= 60) {
                $transaction->aging_bucket = '31-60';
            } elseif ($transaction->days_old <= 90) {
                $transaction->aging_bucket = '61-90';
            } else {
                $transaction->aging_bucket = '90+';
            }
            
            return $transaction;
        });

    return view('aging-report.detailed', compact('transactions'));
}

public function customerAging()
{
    $agingData = $this->calculateAging('customer', true);
    
    
    return view('aging-report.customer', [
        'summary' => $agingData['summary'],
        'transactions' => $agingData['transactions'],
        'customers' => Customer::all()->keyBy('id') // Load all customers
    ]);
}

public function supplierAging()
{
    $agingData = $this->calculateAging('supplier', true);
    
    return view('aging-report.supplier', [
        'summary' => $agingData['summary'],
        'transactions' => $agingData['transactions'],
    ]);
}

public function calculateAging($accountType = 'customer', $includeDetails = false)
{
    $grouped = Outstanding::where('account_type', $accountType)
        ->orderBy('date')
        ->get()
        ->groupBy('account_id');

    $summary = [
        '0-30' => ['count' => 0, 'amount' => 0],
        '31-60' => ['count' => 0, 'amount' => 0],
        '61-90' => ['count' => 0, 'amount' => 0],
        '90+' => ['count' => 0, 'amount' => 0],
        'total' => ['count' => 0, 'amount' => 0],
    ];

    $transactions = [];

    foreach ($grouped as $accountId => $records) {
        $totalPayment = 0;
        $totalReceipt = 0;
        $lastZeroDate = null;

        foreach ($records as $record) {
            $totalPayment += $record->payment;
            $totalReceipt += $record->receipt;

            $balance = $totalReceipt - $totalPayment;

            // Track the last date when balance was zero
            if (round($balance, 2) == 0) {
                $lastZeroDate = $record->date;
            }

            $amount = $record->receipt - $record->payment;

            // Only consider if amount is positive (customer owes)
            if ($amount > 0) {
                $baseDate = $lastZeroDate ?? $records->first()->date;
                $daysOld = \Carbon\Carbon::parse($baseDate)->diffInDays(now());

                // Assign days_old to be used in the view
                $record->days_old = $daysOld;
                $record->amount = $amount;

                // Add to transactions if details needed
                if ($includeDetails) {
                    $transactions[] = $record;
                }

                // Add to aging summary
                if ($daysOld <= 30) {
                    $bucket = '0-30';
                } elseif ($daysOld <= 60) {
                    $bucket = '31-60';
                } elseif ($daysOld <= 90) {
                    $bucket = '61-90';
                } else {
                    $bucket = '90+';
                }

                $summary[$bucket]['count'] += 1;
                $summary[$bucket]['amount'] += $amount;
                $summary['total']['count'] += 1;
                $summary['total']['amount'] += $amount;
            }
        }
    }

    return [
        'summary' => $summary,
        'transactions' => $transactions,
    ];
}

}

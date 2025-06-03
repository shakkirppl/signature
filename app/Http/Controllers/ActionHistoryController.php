<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActionHistory;
use App\Models\AccountHead;
use App\Models\BankMaster;
use App\Models\PaymentVoucher;
use App\Models\Shipment;
use App\Models\Customer;

class ActionHistoryController extends Controller
{
    public function report(Request $request)
    {
        $query = ActionHistory::query();

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
         if ($request->filled('page_name')) {
        $query->where('page_name', $request->page_name);
    }

        $histories = $query->latest()->get();

       
        $coaNames = AccountHead::pluck('name', 'id')->toArray();
        $bankNames = BankMaster::pluck('bank_name', 'id')->toArray();
        $shipments = Shipment::pluck('shipment_no', 'id')->toArray();
        $customers = Customer::pluck('customer_name', 'id')->toArray();
        $pageNames = ActionHistory::select('page_name')->distinct()->pluck('page_name');


        return view('actionhistory.report', compact('histories', 'coaNames', 'bankNames','pageNames','shipments','customers'));
    }
}


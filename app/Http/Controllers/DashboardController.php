<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Shipment;
use App\Models\InspectionDetail;
use App\Models\PurchaseConformation;
use App\Models\NewSlaughterTime;
use App\Models\Outstanding; 
use App\Models\Supplier;  
use App\Models\PurchaseOrder;
use App\Models\RequestingForm;   
class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        try {
  
            $name= Auth::user()->name;
            $total = Shipment::where('shipment_status', 0)
            
            ->pluck('shipment_no')
            ->map(function($no) {
                return (int) filter_var($no, FILTER_SANITIZE_NUMBER_INT);
            })
            ->max();
            $active = 0;
            $deactive = 0;
            $due = 0;
            $recent_store=[];
            // $totalProducts = InspectionDetail::whereHas('inspection', function ($query) {
            //     $query->where('weight_status', 1);
            // })->count();
            $totalProducts = InspectionDetail::whereHas('inspection', function ($query) {
                $query->where('weight_status', 1);
                })->sum('received_qty');

            $debitamount = PurchaseConformation::where('balance_amount', '>', 0)->sum('balance_amount');
            $nextSchedule = NewSlaughterTime::orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->first();
            $outstandings = Outstanding::select(
                'account_id',
                \DB::raw('SUM(payment) as total_payment'),
                \DB::raw('SUM(receipt) as total_receipt')
            )
            ->where('account_type', 'supplier')
            ->groupBy('account_id')
            ->get();

        $sumGreen = 0;

        foreach ($outstandings as $outstanding) {
            if ($outstanding->total_payment < $outstanding->total_receipt) {
                $sumGreen += ($outstanding->total_receipt - $outstanding->total_payment);
            }
        }
        $outstandings = Outstanding::select(
            'account_id',
            \DB::raw('SUM(payment) as total_payment'),
            \DB::raw('SUM(receipt) as total_receipt')
        )
        ->where('account_type', 'customer')
        ->groupBy('account_id')
        ->get();

    $totalPositive = 0;

    foreach ($outstandings as $outstanding) {
        $payment = $outstanding->total_payment;
        $receipt = $outstanding->total_receipt;

        if ($receipt > $payment) {
            $totalPositive += ($receipt - $payment);
        }
    }
   $purchaseOrderCount = PurchaseOrder::whereHas('shipment', function ($query) {
        $query->where('shipment_status', 0);
    })
    ->withoutTrashed()
    ->count();

        $pendingCount = RequestingForm::where('status', 'pending')->count();


            return view('admin',['now' => Carbon::now()->toDateString(),'name' => $name,'total' => $total,'active'=>$active,'deactive'=>$deactive,'due'=>$due,
            'recent_store'=>$recent_store,'totalProducts'=>$totalProducts,'debitamount'=>$debitamount,'nextSchedule'=>$nextSchedule,'sumGreen' => $sumGreen,
            'totalPositive'=>$totalPositive,'purchaseOrderCount'=>$purchaseOrderCount,'pendingCount'=>$pendingCount]);
 
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    }
}

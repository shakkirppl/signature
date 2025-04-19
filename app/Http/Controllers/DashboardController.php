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
class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        try {
  
            $name= Auth::user()->name;
            $total =Shipment::pluck('shipment_no')
            ->map(function($no) {
                return (int) filter_var($no, FILTER_SANITIZE_NUMBER_INT);
            })
            ->max();
            $active = 0;
            $deactive = 0;
            $due = 0;
            $recent_store=[];
            $totalProducts = InspectionDetail::whereHas('inspection', function ($query) {
                $query->where('weight_status', 1);
            })->count();
            $debitamount = PurchaseConformation::where('balance_amount', '>', 0)->sum('balance_amount');
            $nextSchedule = NewSlaughterTime::orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->first();

            return view('admin',['now' => Carbon::now()->toDateString(),'name' => $name,'total' => $total,'active'=>$active,'deactive'=>$deactive,'due'=>$due,
            'recent_store'=>$recent_store,'totalProducts'=>$totalProducts,'debitamount'=>$debitamount,'nextSchedule'=>$nextSchedule]);
 
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    }
}

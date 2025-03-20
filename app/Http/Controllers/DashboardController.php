<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Shipment;
use App\Models\InspectionDetail;
use App\Models\PurchaseConformation;
class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        try {
  
            $name= Auth::user()->name;
            $total = Shipment::count(); 
            $active = 0;
            $deactive = 0;
            $due = 0;
            $recent_store=[];
            $totalProducts = InspectionDetail::whereHas('inspection', function ($query) {
                $query->where('weight_status', 1);
            })->count();
            $debitamount = PurchaseConformation::where('balance_amount', '>', 0)->sum('balance_amount');

            return view('admin',['now' => Carbon::now()->toDateString(),'name' => $name,'total' => $total,'active'=>$active,'deactive'=>$deactive,'due'=>$due,'recent_store'=>$recent_store,'totalProducts'=>$totalProducts,'debitamount'=>$debitamount]);
 
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    }
}

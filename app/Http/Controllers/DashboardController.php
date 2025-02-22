<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Shipment;
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
            return view('admin',['now' => Carbon::now()->toDateString(),'name' => $name,'total' => $total,'active'=>$active,'deactive'=>$deactive,'due'=>$due,'recent_store'=>$recent_store]);
 
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    }
}

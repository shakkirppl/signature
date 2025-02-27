<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;
use App\Models\SlaughterSchedule;
use App\Models\SlaughterScheduleDetail;
use Illuminate\Support\Facades\DB;

class SlaughterScheduleController extends Controller
{

    public function index()
{
    $schedules = SlaughterSchedule::with('details.products')->get();
    return view('slaughter_schedule.index', compact('schedules'));
}

    public function create()
    {
        $products = Product::all(); 
        return view('slaughter_schedule.create',['invoice_no'=>$this->invoice_no()], compact('products'));
    }


    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('slaughter_no',Auth::user()->store_id=1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

    public function store(Request $request)
{
    // return $request->all();
    $request->validate([
        'slaughter_no' => 'required',
        'date' => 'required|date',
        'transportation_date'=>'required',
        'transportation_time'=>'required',
        'loading_time' => 'required',
        'airport_time' => 'required',
        'airline_name' => 'required',
        'airline_number' => 'required',
        'airline_date' => 'required|date',
        'airline_time' => 'required',
        'starting_time_of_slaughter' => 'required',
        'ending_time_of_slaughter' => 'required',
        'products' => 'required|array|min:1',
        'products.*' => 'exists:product,id', 
    ]);

    try {
        DB::beginTransaction();

        // Insert into slaughter_schedules_master
        $slaughterMaster = SlaughterSchedule::create([
            'slaughter_no' => $request->slaughter_no,
            'date' => $request->date,
            'transportation_date' => $request->transportation_date,
            'transportation_time' => $request->transportation_time,
            'loading_time' => $request->loading_time,
            'airport_time' => $request->airport_time,
            'airline_name' => $request->airline_name,
            'airline_number' => $request->airline_number,
            'airline_date' => $request->airline_date,
            'airline_time' => $request->airline_time,
            'starting_time_of_slaughter' => $request->starting_time_of_slaughter,
            'ending_time_of_slaughter' => $request->ending_time_of_slaughter,
            'user_id' => auth()->id(),
            'store_id' => 1,  // Change as per your logic
        ]);

        // Insert multiple products into slaughter_schedules_detail
        if (!empty($request->products)) {
            foreach ($request->products as $productId) {
                if ($productId) { // Ensure valid product ID
                    SlaughterScheduleDetail::create([
                        'slaughter_master_id' => $slaughterMaster->id,
                        'product_id' => $productId,
                    ]);
                }
            }
        }
        InvoiceNumber::updateinvoiceNumber('slaughter_no',1);
        DB::commit();

        return redirect()->route('slaughter.index')->with('success', 'Slaughter schedule updated successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Payment voucher store error: ' . $e->getMessage());

        return redirect()->back()->with('error', 'Error creating schedule: ' . $e->getMessage());
    }
}

public function viewProducts($id)
{
    $schedules = SlaughterSchedule::with('details.products')->get(); // Get multiple schedules
    return view('slaughter_schedule.view', compact('schedules'));
    
}


public function edit($id)
{
    $schedule = SlaughterSchedule::with('details')->findOrFail($id);
    $products = Product::all();
    
    return view('slaughter_schedule.edit', compact('schedule', 'products'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'slaughter_no' => 'required',
        'date' => 'required|date',
        'transportation_date'=>'required',
        'transportation_time'=>'required',
        'loading_time' => 'required',
        'airport_time' => 'required',
        'airline_name' => 'required',
        'airline_number' => 'required',
        'airline_date' => 'required|date',
        'airline_time' => 'required',
        'starting_time_of_slaughter' => 'required',
        'ending_time_of_slaughter' => 'required',
        'products' => 'required|array|min:1',
        'products.*' => 'exists:product,id',
    ]);

    try {
        DB::beginTransaction();

        $schedule = SlaughterSchedule::findOrFail($id);
        $schedule->update([
            'slaughter_no' => $request->slaughter_no,
            'date' => $request->date,
            'transportation_date' => $request->transportation_date,
            'transportation_time' => $request->transportation_time,
            'loading_time' => $request->loading_time,
            'airport_time' => $request->airport_time,
            'airline_name' => $request->airline_name,
            'airline_number' => $request->airline_number,
            'airline_date' => $request->airline_date,
            'airline_time' => $request->airline_time,
            'starting_time_of_slaughter' => $request->starting_time_of_slaughter,
            'ending_time_of_slaughter' => $request->ending_time_of_slaughter,
        ]);

        // Delete old products and insert new ones
        SlaughterScheduleDetail::where('slaughter_master_id', $id)->delete();

        foreach ($request->products as $productId) {
            SlaughterScheduleDetail::create([
                'slaughter_master_id' => $id,
                'product_id' => $productId,
            ]);
        }

        DB::commit();

        return redirect()->route('slaughter.index')->with('success', 'Slaughter schedule updated successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('update  store error: ' . $e->getMessage());

        return redirect()->back()->with('error', 'Error updating schedule: ' . $e->getMessage());
    }
}

public function destroy($id)
{
    try {
        DB::beginTransaction();

        
        SlaughterScheduleDetail::where('slaughter_master_id', $id)->delete();
        
       
        SlaughterSchedule::findOrFail($id)->delete();

        DB::commit();

        return redirect()->route('slaughter-schedule.index')->with('success', 'Slaughter schedule deleted successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Error deleting schedule: ' . $e->getMessage());
    }
}


}
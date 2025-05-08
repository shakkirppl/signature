<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;
use App\Models\SlaughterSchedule;
use App\Models\SlaughterScheduleDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('slaughter_no',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

    public function store(Request $request)
{
    // return $request->all();
    $request->validate([
        'slaughter_no' => 'required',
        'date' => 'nullable|date',
        'transportation_date'=>'nullable',
        'transportation_time'=>'nullable',
        'loading_time' => 'nullable',
        'airport_time' => 'nullable',
        'airline_name' => 'nullable',
       
        'airline_number' => 'nullable',
        'airline_date' => 'nullable|date',
        'airline_time' => 'nullable',
        'starting_time_of_slaughter' => 'nullable',
        'ending_time_of_slaughter' => 'nullable',
        'products' => 'nullable|array|min:1',
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
            'loading_start_date' => $request->loading_start_date,
            'loading_end_date' => $request->loading_end_date,
            'loading_time' => $request->loading_time,
            'loading_end_time' => $request->loading_end_time,
            'airport_time' => $request->airport_time,
            
            'airline_name' => $request->airline_name,
            'airline_number' => $request->airline_number,
            'airline_date' => $request->airline_date,
            'airline_time' => $request->airline_time,
            'slaughter_date'=> $request->slaughter_date,
            'slaughter_end_date'=> $request->slaughter_end_date,
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
        'date' => 'nullable|date',
        'transportation_date'=>'nullable',
        'transportation_time'=>'nullable',
        'loading_time' => 'nullable',
        'airport_time' => 'nullable',
        'airline_name' => 'nullable',
        'airline_number' => 'nullable',
        'airline_date' => 'nullable|date',
        'airline_time' => 'nullable',
        'starting_time_of_slaughter' => 'nullable',
        'ending_time_of_slaughter' => 'nullable',
        'products' => 'nullable|array|min:1',
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
            'loading_start_date' => $request->loading_start_date,
            'loading_end_date' => $request->loading_end_date,
            'loading_time' => $request->loading_time,
            'loading_end_time' => $request->loading_end_time,
            'airport_time' => $request->airport_time,
           
            'airline_name' => $request->airline_name,
            
            'airline_number' => $request->airline_number,
            'airline_date' => $request->airline_date,
            'airline_time' => $request->airline_time,
            'slaughter_date'=> $request->slaughter_date,
            'slaughter_end_date'=> $request->slaughter_end_date,
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
        $slaughterSchedule = SlaughterSchedule::findOrFail($id);

        // Delete related details first
        $slaughterSchedule->details()->delete();

        // Delete the main record
        $slaughterSchedule->delete();
        InvoiceNumber::decreaseInvoice('slaughter_no', 1);
        return redirect()->route('slaughter.index')->with('success', 'Packing list deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('slaughter.index')->with('error', 'Error deleting packing list: ' . $e->getMessage());
    }
}





public function print($id)
{
    $schedule = SlaughterSchedule::with('details.products')->findOrFail($id);
    return view('slaughter_schedule.print', compact('schedule'));
}

// public function uploadScreenshot(Request $request)
// {
//     try {
//         if (!$request->hasFile('image')) {
//             return response()->json(['error' => 'No image file received'], 400);
//         }

//         $path = $request->file('image')->store('screenshots', 'public');
//         if (!$path) {
//             return response()->json(['error' => 'File upload failed'], 500);
//         }

//         $url = asset('storage/' . $path);
//         return response()->json(['url' => $url]);
//     } catch (\Exception $e) {
//         Log::error("Upload Error: " . $e->getMessage());
//         return response()->json(['error' => 'Internal server error'], 500);
//     }
// }


}
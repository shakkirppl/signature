<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Employee;
use App\Models\Product;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SkinningMaster;
use App\Models\SkinningDetail;




class SkinningController extends Controller
{
    public function create()
    {
        
        $shipments = Shipment::where('shipment_status', 0)->get();
        $employees = Employee::where('designation_id', '2')->get();
        $products = Product::all();

        return view('skinning.create',['invoice_no'=>$this->invoice_no()], compact('shipments', 'employees', 'products'));
    }

    // public function index()
    // {
    //     $skinningRecords = SkinningMaster::with(['shipment', 'skinningDetails.employee'])
    //         ->orderBy('date', 'desc')
    //         ->get();
    
    //     return view('skinning.index', compact('skinningRecords'));
    // }

    public function index()
{
    $skinningRecords = SkinningMaster::with(['shipment', 'skinningDetails.employee'])
        ->whereHas('shipment', function ($query) {
            $query->where('shipment_status', 0);
        })
        ->orderBy('date', 'desc')
        ->get();

    return view('skinning.index', compact('skinningRecords'));
}

    
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('skinning_code',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }
                 public function store(Request $request)
                 {
                     $request->validate([
                         'skinning_code' => 'required|unique:skinning_master,skinning_code',
                         'date' => 'required|date',
                         'shipment_id' => 'required|exists:shipment,id',
                         'employees' => 'required|array',
                         'employees.*' => 'exists:employee,id',
                         'products' => 'required|array',
                         'products.*' => 'exists:product,id',
                         'quandity' => 'required|array',
                         'quandity.*' => 'numeric|min:1',
                         'damaged_quandity' => 'nullable|array',
                         'damaged_quandity.*' => 'numeric|min:0',
                         'skin_percentage' => 'required|array',
                         'skin_percentage.*' => 'string|min:0|max:100',
                     ]);
                 
                     try {
                         DB::beginTransaction();
                         $currentTime = Carbon::now()->format('H:i:s'); 
                 
                         // Create Skinning Master Entry (Only One per skinning_code)
                         $skinningMaster = SkinningMaster::create([
                             'skinning_code' => $request->skinning_code,
                             'date' => $request->date,
                             'time' => $currentTime, 
                             'shipment_id' => $request->shipment_id,
                             'user_id' => auth()->id(),
                             'store_id' => 1,
                             'status' => 1,
                         ]);
                 
                         // Loop through Employees and Store Each One with Their Product Details
                         foreach ($request->employees as $index => $employee_id) {
                             SkinningDetail::create([
                                 'skinning_id' => $skinningMaster->id,
                                 'employee_id' => $employee_id,
                                 'product_id' => $request->products[$index],
                                 'damaged_quandity'=> $request->damaged_quandity[$index],
                                 'quandity' => $request->quandity[$index],
                                 'skin_percentage' => $request->skin_percentage[$index],
                                 'store_id' => 1,
                             ]);
                         }
                         InvoiceNumber::updateinvoiceNumber('skinning_code',1);

                         DB::commit();
                         return redirect()->route('skinning.index')->with('success', 'Skinning record created successfully!');
                     } catch (\Exception $e) {
                         DB::rollBack();
                         return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
                     }
                 }
                 
                 
                 

public function edit($id)
{
    $skinning = SkinningMaster::with('skinningDetails')->findOrFail($id);
    $shipments = Shipment::where('shipment_status', 0)->get();
    $employees = Employee::where('designation_id', '1')->get();
    $products = Product::all();

    return view('skinning.edit', compact('skinning', 'shipments', 'employees', 'products'));
}


public function update(Request $request, $id)
{
    
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'shipment_id' => 'required|exists:shipment,id',
        'employees.*' => 'required|exists:employee,id',
        'products.*' => 'required|exists:product,id',
        'quandity.*' => 'required|integer|min:1',
       'damaged_quandity.*'=> 'nullable|integer',
    ]);

    
    DB::beginTransaction();

    try {
        $skinning = SkinningMaster::findOrFail($id);
        $skinning->date = $request->date;
        $skinning->time = $request->time;
        $skinning->shipment_id = $request->shipment_id;
        $skinning->save();

        $skinning->skinningDetails()->delete();

       
        foreach ($request->employees as $index => $employee_id) {
            SkinningDetail::create([
                'skinning_id' => $skinning->id,
                'employee_id' => $employee_id,
                'product_id' => $request->products[$index],
                'quandity' => $request->quandity[$index],
                'damaged_quandity'=> $request->damaged_quandity[$index],
                'skin_percentage' => $request->skin_percentage[$index],
                'store_id' => 1, 
            ]);
        }

      
        DB::commit();

        
        return redirect()->route('skinning.index')->with('success', 'Skinning record updated successfully.');

    } catch (\Exception $e) {
        
        DB::rollBack();
        return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
    }
}


public function view($id)
{
    
    $skinning = SkinningMaster::with('skinningDetails.product', 'skinningDetails.employee')->findOrFail($id);

   
    return view('skinning.view', compact('skinning'));
}

public function destroy($id)
{
    try {
        
        $skinning = SkinningMaster::where('id', $id)->first();


        if (!$skinning) {
            return redirect()->route('skinning.index')->with('error', 'Record not found!');
        }

        SkinningDetail::where('skinning_id', $id)->delete();

        $skinning->delete();

        return redirect()->route('skinning.index')->with('success', 'Sales order and its details have been deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('skinning.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}



public function report(Request $request)
{
    $query = SkinningMaster::with(['shipment', 'skinningDetails.employee'])
        ->orderBy('date', 'desc');

   
    if ($request->has('shipment_id') && $request->shipment_id) {
        $query->where('shipment_id', $request->shipment_id);
    }

    
    if ($request->has('employee_id') && $request->employee_id) {
        $query->whereHas('skinningDetails', function ($q) use ($request) {
            $q->where('employee_id', $request->employee_id);
        });
    }

   
    if ($request->has('from_date') && $request->from_date) {
        $query->whereDate('date', '>=', $request->from_date);
    }
    if ($request->has('to_date') && $request->to_date) {
        $query->whereDate('date', '<=', $request->to_date);
    }

    $skinningRecords = $query->get();
    $employees = Employee::where('designation_id', 1)->get();
    $shipments = Shipment::all();

    return view('skinning.report', compact('skinningRecords', 'shipments', 'employees'));
}



}

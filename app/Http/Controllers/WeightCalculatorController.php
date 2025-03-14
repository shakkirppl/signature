<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\WeightCalculatorMaster;
use App\Models\WeightCalculatorDetail;
use App\Models\Product;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth; 
use App\Models\Supplier;
use App\Models\InspectionDetail;
use App\Models\Shipment;

class WeightCalculatorController extends Controller
{
    public function index()
{
    $shipments = Inspection::join('shipment', 'inspection.shipment_id', '=', 'shipment.id')
        ->where('shipment.shipment_status', 0) 
        ->select('inspection.shipment_id', 'shipment.shipment_no')
        ->distinct()
        ->get();

    return view('weight-calculator.index', compact('shipments'));
}



    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('weight_calculator',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

                 public function create($shipment_id)
                 {
                    $inspection = Inspection::where('shipment_id', $shipment_id)->first();
                     $shipment = Shipment::find($shipment_id);
                     $shipment_no = $shipment ? $shipment->shipment_no : null;
                 
                     // Fetch suppliers linked to this shipment
                     $suppliers = Supplier::whereIn('id', function ($query) use ($shipment_id) {
                         $query->select('supplier_id')
                             ->from('inspection')
                             ->where('shipment_id', $shipment_id);
                     })->get();
                 
                     // Fetch Inspection Details
                     $inspectionDetails = InspectionDetail::whereHas('inspection', function ($query) use ($shipment_id) {
                         $query->where('shipment_id', $shipment_id);
                     })->with('product')->get();
                 
                     return view('weight-calculator.create', [
                         'invoice_no' => $this->invoice_no(),
                         'shipment_id' => $shipment_id,
                         'shipment_no' => $shipment_no,
                         'suppliers' => $suppliers,
                         'inspectionDetails' => $inspectionDetails,
                         'inspection' => $inspection,
                     ]);
                 } 

                 
                 
                 
                 public function store(Request $request)
                 {
                    // return $request->all();
                     $validated = $request->validate([
                        'date' => 'required|date',
                        'weight_code' => 'required|string',
                        'shipment_id' => 'required|integer',
                        'supplier_id' => 'required|integer',
                        'total_weight' => 'required|numeric',
                        'weight' => 'required|array', // Ensure weight inputs exist
                        'weight.*' => 'required|numeric|min:0',
                        'inspection.*' => 'required|exists:inspection,id',
                        'purchaseOrder_id'=>'required|exists:purchase_order,id',

                     ]);
                 
                     \DB::beginTransaction();
                     try {
                         // Use the supplier ID directly from the request
                         $supplierId = $request->supplier_id;
                     
                         $existingWeightCalculator = WeightCalculatorMaster::where('shipment_id', $request->shipment_id)
                             ->where('supplier_id', $supplierId)
                             ->first();
                     
                         if ($existingWeightCalculator) {
                            WeightCalculatorDetail::where('weight_master_id',$existingWeightCalculator->id)->delete();
                            $existingWeightCalculator->delete();
                            //  return redirect()->back()->with('error', 'Weight calculation already exists for this supplier in this shipment.');
                         }
                     
                         // Store WeightCalculatorMaster
                         $weightCalculatorMaster = new WeightCalculatorMaster();
                         $weightCalculatorMaster->weight_code = $request->weight_code;
                         $weightCalculatorMaster->date = $request->date;
                         $weightCalculatorMaster->shipment_id = $request->shipment_id;
                         $weightCalculatorMaster->purchaseOrder_id = $request->purchaseOrder_id ?? null;
                         $weightCalculatorMaster->inspection_id = $request->inspection_id ?? null;
                         $weightCalculatorMaster->supplier_id = $supplierId;
                         $weightCalculatorMaster->total_weight = $request->total_weight;
                         $weightCalculatorMaster->user_id = Auth::id();
                         $weightCalculatorMaster->store_id = 1;
                         $weightCalculatorMaster->status = 1;
                         $weightCalculatorMaster->save();
                     
                         // Loop through products
                         foreach ($request->product_id as $index => $productId) {
                            $weightCalculatorDetail = new WeightCalculatorDetail();
                            $weightCalculatorDetail->weight_master_id = $weightCalculatorMaster->id;
                            $weightCalculatorDetail->product_id = $productId;
                            $weightCalculatorDetail->male_accepted_qty = $request->male_accepted_qty[$index] ?? 0;
                            $weightCalculatorDetail->female_accepted_qty = $request->female_accepted_qty[$index] ?? 0;
                            $weightCalculatorDetail->total_accepted_qty = $request->total_accepted_qty[$index] ?? 0;
                            $weightCalculatorDetail->weight = $request->weight[$index] ?? 0;
                            $weightCalculatorDetail->supplier_id = $supplierId;
                            $weightCalculatorDetail->shipment_id = $request->shipment_id;
                            
                           
                            $weightCalculatorDetail->save();
                        }
                        
                     
                         // Update Invoice Number
                         InvoiceNumber::updateinvoiceNumber('weight_calculator', 1);
                     
                         \DB::commit();
                     
                         return redirect()->route('weight_calculator.index')->with('success', 'Weight Calculator data saved successfully.');
                     } catch (\Exception $e) {
                         \DB::rollback();
                         \Log::error('Weight Calculator Store Error', ['error' => $e->getMessage(), 'request' => $request->all()]);
                         
                         return redirect()->back()->with('error', 'Failed to save Weight Calculator data. Please try again.');
                     }
                     
                 }
                 

public function getSuppliersByShipment($shipment_id) {
    $suppliers = Inspection::where('shipment_id', $shipment_id)
                    ->distinct()
                    ->pluck('supplier_name', 'supplier_id');
    return response()->json($suppliers);
}





public function getSupplierProducts(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    $inspectionDetails = InspectionDetail::whereHas('inspection', function ($query) use ($supplier_id, $shipment_id) {
        $query->where('shipment_id', $shipment_id)
              ->where('supplier_id', $supplier_id);
    })->with('product')->get();

    return response()->json($inspectionDetails);
}

public function checkExistingWeightCalculation(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    $exists = WeightCalculatorMaster::where('shipment_id', $shipment_id)
        ->where('supplier_id', $supplier_id)
        ->exists();

    return response()->json(['exists' => $exists]);
}

public function getPurchaseOrderId(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    $inspection = Inspection::where('shipment_id', $shipment_id)
        ->where('supplier_id', $supplier_id)
        ->first();

    if ($inspection) {
        return response()->json(['purchaseOrder_id' => $inspection->purchaseOrder_id]);
    } else {
        return response()->json(['purchaseOrder_id' => null]);
    }
}




public function getExistingWeightCalculation(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    $weightMaster = WeightCalculatorMaster::where('shipment_id', $shipment_id)
        ->where('supplier_id', $supplier_id)
        ->first();

    if (!$weightMaster) {
        return response()->json(['error' => 'Weight calculation not found'], 404);
    }

    $weightDetails = WeightCalculatorDetail::where('weight_master_id', $weightMaster->id)->get();

    return view('weight-calculator.edit-modal', compact('weightMaster', 'weightDetails'));
}


public function updateWeightCalculation(Request $request)
{
    \DB::beginTransaction();

    try {
        $weightMaster = WeightCalculatorMaster::findOrFail($request->weight_master_id);
        $weightMaster->date = $request->date;
        $weightMaster->total_weight = $request->total_weight; // Save total weight
        $weightMaster->save();

        foreach ($request->quandity as $detail_id => $quantity) {
            $detail = WeightCalculatorDetail::findOrFail($detail_id);
            $detail->quandity = $quantity;
            $detail->weight = $request->weight[$detail_id];
            $detail->save();
        }

        \DB::commit();
        return response()->json(['success' => 'Weight calculation updated successfully.']);
    } catch (\Exception $e) {
        \DB::rollback();
        return response()->json(['error' => 'Failed to update weight calculation.'], 500);
    }
}
    



public function report(Request $request) {
    $query = WeightCalculatorMaster::with('shipment');
    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }
    if ($request->has('shipment_id') && $request->shipment_id != '') {
        $query->where('shipment_id', $request->shipment_id);
    }
    $weightMasters = $query->get();
    $shipments = Inspection::join('shipment', 'inspection.shipment_id', '=', 'shipment.id')
        ->select('inspection.shipment_id', 'shipment.shipment_no')
        ->distinct()
        ->get();
    return view('weight-calculator-report.report', compact('weightMasters', 'shipments'));
}


public function reportview($id) {
    $weightMaster = WeightCalculatorMaster::with('shipment')->findOrFail($id);
    $weightDetails = WeightCalculatorDetail::where('weight_master_id', $id)->with('product')->get();
    return view('weight-calculator-report.reportview', compact('weightMaster', 'weightDetails'));
}
                
}

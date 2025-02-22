<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseConformation;
use App\Models\WeightCalculatorMaster;
use App\Models\WeightCalculatorDetail;
use App\Models\Product;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\PurchaseConformationDetail;
use App\Models\Shipment;

class WeightCalculatorController extends Controller
{
    public function index()
{
    $shipments = PurchaseConformation::join('shipment', 'purchase_conformation.shipment_id', '=', 'shipment.id')
        ->where('shipment.shipment_status', 0) 
        ->select('purchase_conformation.shipment_id', 'shipment.shipment_no')
        ->distinct()
        ->get();

    return view('weight-calculator.index', compact('shipments'));
}

    
    


    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('weight_calculator',Auth::user()->store_id=1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

                 public function create($shipment_id)
                 {
                     $purchaseConformation = PurchaseConformation::where('shipment_id', $shipment_id)->first();
                 
                     // Get the shipment number using the shipment_id
                     $shipment = Shipment::find($shipment_id);
                     $shipment_no = $shipment ? $shipment->shipment_no : null;
                 
                     // Get all suppliers linked to this shipment
                     $suppliers = Supplier::whereIn('id', function ($query) use ($shipment_id) {
                         $query->select('supplier_id')
                             ->from('purchase_conformation')
                             ->where('shipment_id', $shipment_id);
                     })->get();
                 
                     // Get existing weight calculations for the shipment
                     $existingWeightCalculations = WeightCalculatorMaster::where('shipment_id', $shipment_id)->pluck('supplier_id')->toArray();
                 
                     return view('weight-calculator.create', [
                         'invoice_no' => $this->invoice_no(),
                         'shipment_id' => $shipment_id,
                         'shipment_no' => $shipment_no,
                         'suppliers' => $suppliers,
                         'existingWeightCalculations' => $existingWeightCalculations, // Pass existing weight calculations
                     ]);
                 }
                 
                 

                 

                 public function store(Request $request)
                 {
                    
                     $validated = $request->validate([
                         'weight_code' => 'required|string|max:255',
                         'date' => 'required|date',
                         'supplier' => 'required|array',
                         'supplier.*' => 'exists:supplier,id', 
                         'products' => 'required|array',
                         'quandity' => 'required|array',
                         'weight' => 'required|array',
                     ]);
                 
                     \DB::beginTransaction();
                     try {
                        
                         foreach ($request->supplier as $supplierId) {
                             
                             $existingWeightCalculator = WeightCalculatorMaster::where('shipment_id', $request->shipment_id)
                                 ->where('supplier_id', $supplierId)
                                 ->first();
                 
                             
                             if ($existingWeightCalculator) {
                                 return redirect()->back()->with('error', 'Weight calculation already exists for this supplier in this shipment.');
                             }
                 
                             
                             $weightCalculatorMaster = new WeightCalculatorMaster();
                             $weightCalculatorMaster->weight_code = $request->weight_code;
                             $weightCalculatorMaster->date = $request->date;
                             $weightCalculatorMaster->shipment_id = $request->shipment_id;
                             $weightCalculatorMaster->supplier_id = $supplierId;
                             $weightCalculatorMaster->total_weight = $request->total_weight;  
                             $weightCalculatorMaster->user_id = Auth::id();  
                             $weightCalculatorMaster->store_id = 1; 
                             $weightCalculatorMaster->status = 1;  
                             $weightCalculatorMaster->save();
                 
                           
                             foreach ($request->products as $index => $productId) {
                                
                                 $existingDetail = WeightCalculatorDetail::where('weight_master_id', $weightCalculatorMaster->id)
                                     ->where('product_id', $productId)
                                     ->where('supplier_id', $supplierId)
                                     ->first();
                 
                                 if ($existingDetail) {
                                     
                                     $existingDetail->quandity = $request->quandity[$index]; 
                                     $existingDetail->weight = $request->weight[$index];
                                     $existingDetail->shipment_id = $request->shipment_id;  
                                     $existingDetail->save();
                                 } else {
                                    
                                     $weightCalculatorDetail = new WeightCalculatorDetail();
                                     $weightCalculatorDetail->weight_master_id = $weightCalculatorMaster->id;
                                     $weightCalculatorDetail->product_id = $productId;
                                     $weightCalculatorDetail->quandity = $request->quandity[$index]; 
                                     $weightCalculatorDetail->weight = $request->weight[$index];
                                     $weightCalculatorDetail->supplier_id = $supplierId;
                                     $weightCalculatorDetail->shipment_id = $request->shipment_id; 
                                     $weightCalculatorDetail->save();
                                 }
                             }
                         }
                         InvoiceNumber::updateinvoiceNumber('weight_calculator',1);

                         \DB::commit();
                 
                         return redirect()->route('weight_calculator.index')->with('success', 'Weight Calculator data saved successfully.');
                     } catch (\Exception $e) {
                        
                         \DB::rollback();
                         \Log::error('Weight Calculator Store Error: ' . $e->getMessage(), ['exception' => $e]);
                 
                         return redirect()->back()->with('error', 'Failed to save Weight Calculator data. Please try again.');
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
                    $shipments = PurchaseConformation::join('shipment', 'purchase_conformation.shipment_id', '=', 'shipment.id')
                        ->select('purchase_conformation.shipment_id', 'shipment.shipment_no')
                        ->distinct()
                        ->get();
                    return view('weight-calculator-report.report', compact('weightMasters', 'shipments'));
                }


                public function reportview($id) {
                    $weightMaster = WeightCalculatorMaster::with('shipment')->findOrFail($id);
                    $weightDetails = WeightCalculatorDetail::where('weight_master_id', $id)->with('product')->get();
                    return view('weight-calculator-report.reportview', compact('weightMaster', 'weightDetails'));
                }


               
                
        


public function getSuppliersByShipment($shipment_id) {
    $suppliers = PurchaseConfirmation::where('shipment_id', $shipment_id)
                    ->distinct()
                    ->pluck('supplier_name', 'supplier_id');
    return response()->json($suppliers);
}



public function getProductsBySupplier(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    $products = PurchaseConformationDetail::whereHas('conformation', function ($query) use ($supplier_id, $shipment_id) {
        $query->where('supplier_id', $supplier_id)->where('shipment_id', $shipment_id);
    })
    ->with('product')
    ->get();

    return response()->json($products->map(function ($detail) {
        return [
            'id' => $detail->product->id,
            'product_name' => $detail->product->product_name
        ];
    }));
}

public function checkExistingWeightCalculation(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    // Check if a weight calculation already exists for this supplier in the shipment
    $exists = WeightCalculatorMaster::where('shipment_id', $shipment_id)
        ->where('supplier_id', $supplier_id)
        ->exists();

    return response()->json(['exists' => $exists]);
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
    


                
}

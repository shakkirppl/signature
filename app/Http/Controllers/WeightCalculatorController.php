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
    // Validation
    $validated = $request->validate([
        'date' => 'required|date',
        'weight_code' => 'required|string',
        'shipment_id' => 'required|integer',
        'supplier_id' => 'required|integer',
        'total_weight' => 'required|numeric',
        'weight' => 'required|array',
        'weight.*' => 'required|numeric|min:0',
        'inspection.*' => 'required|exists:inspection,id',
        'purchaseOrder_id' => 'required|exists:purchase_order,id',
        'document' => 'required|file',
    ]);

    \DB::beginTransaction();
    try {
        $supplierId = $request->supplier_id;
        $shipmentId = $request->shipment_id;

        // Check if a non-active (status != 1) record already exists
        $nonActiveExists = WeightCalculatorMaster::where('shipment_id', $shipmentId)
            ->where('supplier_id', $supplierId)
            ->whereIn('status', [0, 2, 3])
            ->exists();

        if ($nonActiveExists) {
    return redirect()->back()->with('error', 'Weight has already been taken for this shipment and supplier. You cannot add or update it again.');
        }

        // If an active record exists (status = 1), delete it
        $existingWeightCalculator = WeightCalculatorMaster::where('shipment_id', $shipmentId)
            ->where('supplier_id', $supplierId)
            ->where('status', 1)
            ->first();

        if ($existingWeightCalculator) {
            WeightCalculatorDetail::where('weight_master_id', $existingWeightCalculator->id)->delete();
            $existingWeightCalculator->delete();
        }

        // Store new WeightCalculatorMaster
        $weightCalculatorMaster = new WeightCalculatorMaster();
        $weightCalculatorMaster->weight_code = $request->weight_code;
        $weightCalculatorMaster->date = $request->date;
        $weightCalculatorMaster->shipment_id = $shipmentId;
        $weightCalculatorMaster->purchaseOrder_id = $request->purchaseOrder_id ?? null;
        $weightCalculatorMaster->inspection_id = $request->inspection_id ?? null;
        $weightCalculatorMaster->supplier_id = $supplierId;
        $weightCalculatorMaster->total_weight = $request->total_weight;
        $weightCalculatorMaster->user_id = Auth::id();
        $weightCalculatorMaster->store_id = 1;
        $weightCalculatorMaster->status = 1;

        // Handle file upload
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/documents', $filename, 'public');
            $weightCalculatorMaster->document = $filePath;
        }

        $weightCalculatorMaster->save();

        // Store weight details
        foreach ($request->product_id as $index => $productId) {
            $weightCalculatorDetail = new WeightCalculatorDetail();
            $weightCalculatorDetail->weight_master_id = $weightCalculatorMaster->id;
            $weightCalculatorDetail->product_id = $productId;
            $weightCalculatorDetail->total_accepted_qty = $request->total_accepted_qty[$index] ?? 0;
            $weightCalculatorDetail->weight = $request->weight[$index] ?? 0;
            $weightCalculatorDetail->supplier_id = $supplierId;
            $weightCalculatorDetail->shipment_id = $shipmentId;
            $weightCalculatorDetail->save();
        }

        // Update invoice number tracker
        InvoiceNumber::updateinvoiceNumber('weight_calculator', 1);

        // Commit transaction
        \DB::commit();

        // Update related inspection status
        Inspection::where('id', $request->inspection_id)->update(['weight_status' => 0]);

        return redirect()->route('weight_calculator.index')->with('success', 'Weight Calculator data saved successfully.');
    } catch (\Exception $e) {
        \DB::rollback();
        \Log::error('Weight Calculator Store Error', [
            'error' => $e->getMessage(),
            'request' => $request->all()
        ]);

        return redirect()->back()->with('error', 'Failed to save Weight Calculator data. Please try again.');
    }
}
                
                 
//        public function store(Request $request)
// {
//     // Validation
//     $validated = $request->validate([
//         'date' => 'required|date',
//         'weight_code' => 'required|string',
//         'shipment_id' => 'required|integer',
//         'supplier_id' => 'required|integer',
//         'total_weight' => 'required|numeric',
//         'weight' => 'required|array',
//         'weight.*' => 'required|numeric|min:0',
//         'inspection.*' => 'required|exists:inspection,id',
//         'purchaseOrder_id' => 'required|exists:purchase_order,id',
//         'document' => 'required',
//     ]);

//     \DB::beginTransaction();
//     try {
//         $supplierId = $request->supplier_id;

//         $existingWeightCalculator = WeightCalculatorMaster::where('shipment_id', $request->shipment_id)
//             ->where('supplier_id', $supplierId)
//             ->first();

//         if ($existingWeightCalculator) {
//             WeightCalculatorDetail::where('weight_master_id', $existingWeightCalculator->id)->delete();
//             $existingWeightCalculator->delete();
//         }

//         // Store WeightCalculatorMaster
//         $weightCalculatorMaster = new WeightCalculatorMaster();
//         $weightCalculatorMaster->weight_code = $request->weight_code;
//         $weightCalculatorMaster->date = $request->date;
//         $weightCalculatorMaster->shipment_id = $request->shipment_id;
//         $weightCalculatorMaster->purchaseOrder_id = $request->purchaseOrder_id ?? null;
//         $weightCalculatorMaster->inspection_id = $request->inspection_id ?? null;
//         $weightCalculatorMaster->supplier_id = $supplierId;
//         $weightCalculatorMaster->total_weight = $request->total_weight;
//         $weightCalculatorMaster->user_id = Auth::id();
//         $weightCalculatorMaster->store_id = 1;
//         $weightCalculatorMaster->status = 1;

//         // 📁 Handle file upload
//         if ($request->hasFile('document')) {
//             $file = $request->file('document');
//             $filename = time() . '_' . $file->getClientOriginalName();
//             $filePath = $file->storeAs('uploads/documents', $filename, 'public');
//              // store in storage/app/public/uploads/documents
//             $weightCalculatorMaster->document = $filePath; // assuming column name is 'document'
//         }

//         $weightCalculatorMaster->save();

//         // Store weight details
//         foreach ($request->product_id as $index => $productId) {
//             $weightCalculatorDetail = new WeightCalculatorDetail();
//             $weightCalculatorDetail->weight_master_id = $weightCalculatorMaster->id;
//             $weightCalculatorDetail->product_id = $productId;
//             $weightCalculatorDetail->total_accepted_qty = $request->total_accepted_qty[$index] ?? 0;
//             $weightCalculatorDetail->weight = $request->weight[$index] ?? 0;
//             $weightCalculatorDetail->supplier_id = $supplierId;
//             $weightCalculatorDetail->shipment_id = $request->shipment_id;
//             $weightCalculatorDetail->save();
//         }

//         InvoiceNumber::updateinvoiceNumber('weight_calculator', 1);
//         \DB::commit();

//         Inspection::where('id', $request->inspection_id)->update(['weight_status' => 0]);

//         return redirect()->route('weight_calculator.index')->with('success', 'Weight Calculator data saved successfully.');
//     } catch (\Exception $e) {
//         \DB::rollback();
//         \Log::error('Weight Calculator Store Error', ['error' => $e->getMessage(), 'request' => $request->all()]);

//         return redirect()->back()->with('error', 'Failed to save Weight Calculator data. Please try again.');
//     }
// }

                 

public function getSuppliersByShipment($shipment_id) {
    $suppliers = Inspection::where('shipment_id', $shipment_id)
                    ->distinct()
                    ->pluck('supplier_name', 'supplier_id');
    return response()->json($suppliers);
}


// public function getSupplierProducts(Request $request)
// {
//     $supplier_id = $request->supplier_id;
//     $shipment_id = $request->shipment_id;

//     $inspectionDetails = InspectionDetail::whereHas('inspection', function ($query) use ($supplier_id, $shipment_id) {
//         $query->where('shipment_id', $shipment_id)
//               ->where('supplier_id', $supplier_id);
//     })->with('product')->get();

//     // Filter out products where total_accepted_qty is 0 or below
//     $filteredDetails = $inspectionDetails->filter(function ($detail) {
//         return ($detail->male_accepted_qty + $detail->female_accepted_qty) > 0;
//     });

//     return response()->json($filteredDetails);
// }

public function getSupplierProducts(Request $request)
{
    $supplier_id = $request->supplier_id;
    $shipment_id = $request->shipment_id;

    $inspectionDetails = InspectionDetail::whereHas('inspection', function ($query) use ($supplier_id, $shipment_id) {
        $query->where('shipment_id', $shipment_id)
              ->where('supplier_id', $supplier_id);
    })->with('product')->get();

    // Group by product_id and sum accepted qty
    $grouped = $inspectionDetails->groupBy('product_id')->map(function ($items) {
        $totalMale = $items->sum('male_accepted_qty');
        $totalFemale = $items->sum('female_accepted_qty');
        $totalQty = $totalMale + $totalFemale;

        return [
            'product_id' => $items->first()->product->id,
            'product_name' => $items->first()->product->product_name,
            'total_accepted_qty' => $totalQty,
        ];
    })->values(); // reset index

    return response()->json($grouped);
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



// Facility Manager approval
public function facilityApprove($id)
{
    $calculator = WeightCalculatorMaster::findOrFail($id);

    if ($calculator->status == 1) {
        $calculator->status = 2; // pending Accountant
        $calculator->save();
    }

    return redirect()->back()->with('success', 'Approved by Facility Manager.');
}

// Accountant final approval
public function accountantApprove($id)
{
    $calculator = WeightCalculatorMaster::findOrFail($id);

    if ($calculator->status == 2) {
        $calculator->status = 3; // Final approved
        $calculator->save();
    }

    return redirect()->back()->with('success', 'Final approval by Accountant.');
}


// Facility Manager Pending List
public function pendingFacilityApproval()
{
    $data = WeightCalculatorMaster::where('status', 1)
        ->with(['supplier', 'details']) // eager load supplier and details
        ->get();

    return view('weight-approval.facility', compact('data'));
}

public function pendingAccountantApproval()
{
    $data = WeightCalculatorMaster::where('status', 2)
        ->with(['supplier', 'details']) // eager load supplier and details
        ->get();

    return view('weight-approval.accountant', compact('data'));
}

    
                
}

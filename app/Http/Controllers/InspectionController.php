<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\RejectMaster;
use App\Models\Inspection;
use App\Models\InspectionDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceNumber;

use Illuminate\Support\Facades\Log;




class InspectionController extends Controller
{
    public function index()
{
    
    $inspections = PurchaseOrder::where('inspection_status', 0)->get();

    return view('inspection.index', compact('inspections'));
}

public function invoice_no(){
    try {
         
     return $invoice_no =  InvoiceNumber::ReturnInvoice('inspection_no',1);
              } catch (\Exception $e) {
     
        return $e->getMessage();
      }
             }

public function view($id)
{
    $purchaseOrder = PurchaseOrder::with(['details.product', 'supplier','shipments'])->findOrFail($id);
    $suppliers = Supplier::all(); 
    $products = Product::all();   
    $rejectReasons=RejectMaster::all();
   $shipments = Shipment::where('shipment_status', 0)
    ->orWhere('id', $purchaseOrder->shipment_id) // include the selected one
    ->get();

    return view('inspection.view', compact('purchaseOrder', 'suppliers', 'products','rejectReasons','shipments'),[ 'invoice_no' => $this->invoice_no()]);
}


public function store(Request $request)
{
    // Validate form data
    // return $request->all();
        $validated = $request->validate([
            'order_no' => 'required|string',
            'inspection_no' => 'required|string',
            'purchaseOrder_id'=>'required',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'shipment_id' => 'required|exists:shipment,id',
            'products.*.product_id' => 'required|exists:product,id',
            'products.*.type' => 'nullable|string',  // Ensure type is validated
            'products.*.mark' => 'nullable|string',
            'products.*.qty' => 'required|integer',
            'products.*.received_qty' => 'required|integer',
            'products.*.male_accepted_qty' => 'required|integer',
            'products.*.female_accepted_qty' => 'required|integer',
            'products.*.male_rejected_qty' => 'nullable|integer',
            'products.*.female_rejected_qty' => 'nullable|integer',
           
            'products.*.rejected_reason' => 'nullable|exists:reject_masters,id',
            'products.*.rate' => 'nullable|numeric',
            'products.*.total' => 'nullable|numeric',
        ]);
        
   

    // Create the inspection record
    $inspection = Inspection::create([
        'purchaseOrder_id' => $validated['purchaseOrder_id'],
        'inspection_no'=>$validated['inspection_no'],
        'order_no' => $validated['order_no'],
        'shipment_id' => $validated['shipment_id'],
        'date' => $validated['date'],
        'supplier_id' => $validated['supplier_id'],
        'total_death_qty' => null,
        'user_id' => Auth::id(),
        'store_id' => 1,
        'status' => 1,
        'purchase_status' => 0,
        'weight_status' => 1,
    ]);
    InvoiceNumber::updateinvoiceNumber('inspection_no', 1);
    // Loop through the product details and create InspectionDetail records
    foreach ($validated['products'] as $product) {
        InspectionDetail::create([
            'inspection_id' => $inspection->id,
            'product_id' => $product['product_id'],
            'type' => null,
            'mark' => null,
            'qty' => $product['qty'],
            'received_qty' => $product['received_qty'],
            'male_accepted_qty' => $product['male_accepted_qty'],
            'female_accepted_qty' => $product['female_accepted_qty'],
            'male_rejected_qty' => $product['male_rejected_qty'],
            'female_rejected_qty' => $product['female_rejected_qty'],
            'rejected_reason' => $product['rejected_reason'],
            'death_male_qty' => $product['death_male_qty'] ?? 0,
            'death_female_qty' => $product['death_female_qty'] ?? 0,
            'rate' => 0,
            'total' =>0,
            'user_id' => Auth::id(),
            'store_id' => 1,
            'status' => 0,
           
        ]);
    }

    // Update the inspection_status in the purchase_orders table
    PurchaseOrder::where('order_no', $validated['order_no'])->update(['inspection_status' => 1]);

    return redirect()->route('inspection.index')->with('success', 'Inspection stored successfully and order status updated.');
}



public function report(Request $request)
{
    $query = Inspection::with('supplier')->where('status', 1);

    // Apply filters
    if ($request->supplier_id) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->from_date && $request->to_date) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    $inspections = $query->get();
    $suppliers = Supplier::all();

    return view('inspection.report', compact('inspections', 'suppliers'));
}


// public function viewReport($id)
// {
//     $inspection = Inspection::with(['supplier', 'details.product'])->findOrFail($id);
//     return view('inspection.reportview', compact('inspection'));
// }


public function viewSupplierRejectedProducts($shipment_no, $supplier_name)
{
    $rejectedProducts = DB::table('inspection_detail')
        ->join('inspection', 'inspection_detail.inspection_id', '=', 'inspection.id')
        ->join('product', 'inspection_detail.product_id', '=', 'product.id')
        ->join('shipment', 'inspection.shipment_id', '=', 'shipment.id')
        ->join('supplier', 'inspection.supplier_id', '=', 'supplier.id')
        ->leftJoin('reject_masters', 'inspection_detail.rejected_reason', '=', 'reject_masters.id')
        ->select(
            'product.product_name',
            'inspection_detail.male_rejected_qty',
            'inspection_detail.female_rejected_qty',
            'reject_masters.rejected_reasons as rejected_reason'
        )
        ->where('shipment.shipment_no', '=', $shipment_no)
        ->where('supplier.name', '=', $supplier_name)
        ->where(function ($query) {
            $query->where('inspection_detail.male_rejected_qty', '>', 0)
                  ->orWhere('inspection_detail.female_rejected_qty', '>', 0);
        })
        ->get();

    return view('inspection.reportview', compact('rejectedProducts', 'supplier_name', 'shipment_no'));
}



public function rejectedAnimalReport()
{
    $rejectedReports = DB::table('inspection_detail')
        ->join('inspection', 'inspection_detail.inspection_id', '=', 'inspection.id')
        ->join('shipment', 'inspection.shipment_id', '=', 'shipment.id')
        ->select('shipment.shipment_no') // Only fetch shipment_no
        ->where(function ($query) {
            $query->where('inspection_detail.male_rejected_qty', '>', 0)
                  ->orWhere('inspection_detail.female_rejected_qty', '>', 0);
        })
        ->where('shipment.shipment_status', '=', 0)
        ->distinct() // Ensures only unique shipment numbers are retrieved
        ->get();

    return view('inspection.rejectedreport', compact('rejectedReports'));
}

public function shipmentRejectedDetails($shipment_no)
{
    $shipmentDetails = DB::table('inspection_detail')
        ->join('inspection', 'inspection_detail.inspection_id', '=', 'inspection.id')
        ->join('product', 'inspection_detail.product_id', '=', 'product.id') 
        ->join('shipment', 'inspection.shipment_id', '=', 'shipment.id')
        ->join('supplier', 'inspection.supplier_id', '=', 'supplier.id') // Join with supplier table
        ->leftJoin('reject_masters', 'inspection_detail.rejected_reason', '=', 'reject_masters.id')
        ->select(
            'shipment.shipment_no',
            'supplier.name as supplier_name', // Grouping by supplier name
            DB::raw('SUM(inspection_detail.male_rejected_qty) as total_male_rejected_qty'),
            DB::raw('SUM(inspection_detail.female_rejected_qty) as total_female_rejected_qty'),
            DB::raw('GROUP_CONCAT(DISTINCT reject_masters.rejected_reasons ORDER BY reject_masters.rejected_reasons ASC SEPARATOR ", ") as rejected_reasons') // Combine reasons
        )
        ->where('shipment.shipment_no', '=', $shipment_no) // Filter by shipment number
        ->where(function ($query) {
            $query->where('inspection_detail.male_rejected_qty', '>', 0)
                  ->orWhere('inspection_detail.female_rejected_qty', '>', 0);
        })
        ->groupBy('shipment.shipment_no', 'supplier.id', 'supplier.name') // Group by supplier
        ->get();

    return view('inspection.shipment_rejected_details', compact('shipmentDetails', 'shipment_no'));
}




public function edit($id)
{
    $inspection = Inspection::with('details.product')->findOrFail($id);
    $suppliers = Supplier::all();
    $rejectReasons = RejectMaster::all(); // Assuming reject reasons are stored here

    return view('inspection.edit', compact('inspection', 'suppliers', 'rejectReasons'));
}

public function update(Request $request, $id)
{
    try {
        DB::beginTransaction(); // Start transaction

        // Validate input data
        $validated = $request->validate([
            'order_no' => 'required|string',
            'inspection_no' => 'required|string',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'products.*.received_qty' => 'nullable|integer',
            'products.*.male_accepted_qty' => 'nullable|integer',
            'products.*.female_accepted_qty' => 'nullable|integer',
            'products.*.male_rejected_qty' => 'nullable|integer',
            'products.*.female_rejected_qty' => 'nullable|integer',
            'products.*.rejected_reason' => 'nullable|exists:reject_masters,id',
        ]);

        // Check validation for received quantity
        foreach ($validated['products'] as $detail_id => $productData) {
            $totalQuantity = ($productData['male_accepted_qty'] ?? 0) + 
                             ($productData['female_accepted_qty'] ?? 0);

            if ($totalQuantity !== (int)$productData['received_qty']) {
                session()->flash('error', "Received quantity must be equal to the sum of accepted and rejected quantities.");
                return redirect()->back();
            }
        }

        // Find the inspection record
        $inspection = Inspection::findOrFail($id);

        // Update inspection details
        $inspection->update([
            'order_no' => $validated['order_no'],
            'inspection_no' => $validated['inspection_no'],
            'date' => $validated['date'],
            'supplier_id' => $validated['supplier_id'],
        ]);

        // Update each inspection detail record
        foreach ($validated['products'] as $detail_id => $productData) {
            $detail = InspectionDetail::findOrFail($detail_id);
            $detail->update([
                'received_qty' =>  isset($productData['received_qty']) ? $productData['received_qty'] : null,
                'male_accepted_qty' => isset($productData['male_accepted_qty']) ? $productData['male_accepted_qty'] : null,
                'female_accepted_qty' => isset($productData['female_accepted_qty']) ? $productData['female_accepted_qty'] : null,
                'male_rejected_qty' => $productData['male_rejected_qty'] ?? 0,
                'female_rejected_qty' => $productData['female_rejected_qty'] ?? 0,
                'rejected_reason' => $productData['rejected_reason'],
            ]);
        }

        DB::commit(); // Commit transaction if everything is fine

        return redirect()->route('inspection.report')->with('success', 'Inspection updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaction if error occurs

        Log::error('Inspection update error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'Failed to update inspection. Check logs for details.');
    }
}




public function inspectionview($id)
{
    // Retrieve the inspection record with related supplier and details
    $inspection = Inspection::with(['supplier', 'details.product','details.rejectMaster'])->findOrFail($id);

    return view('inspection.inspectionview', compact('inspection'));
}

public function destroy($id)
{
    try {
        
        $inspection = Inspection::findOrFail($id);
        $inspection->details()->delete();
        InvoiceNumber::decreaseInvoice('inspection_no', 1);
         $inspection->update(['status' => 0]);

        return redirect()->route('inspection.report')->with('success', 'Inspection status changed successfully and related details deleted.');
    } catch (\Exception $e) {
        return redirect()->route('inspection.report')->with('error', 'Error updating record: ' . $e->getMessage());
    }
}



}

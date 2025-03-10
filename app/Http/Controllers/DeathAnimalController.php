<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\Supplier;
use App\Models\InspectionDetail;
use App\Models\Shipment;


class DeathAnimalController extends Controller
{

    public function create()
{
    // Fetch all shipments with status 0 (Active)
    $shipments = Shipment::where('shipment_status', 0)->get();

    return view('death-animal.create', compact('shipments'));
}
   
    public function fetchProducts(Request $request)
    {
        $inspection = Inspection::where('shipment_id', $request->shipment_id)
                                ->where('supplier_id', $request->supplier_id)
                                ->first();
    
        if (!$inspection) {
            return response()->json(['products' => [], 'inspection' => null]);
        }
    
        $products = InspectionDetail::where('inspection_id', $inspection->id)
            ->join('product', 'inspection_detail.product_id', '=', 'product.id')
            ->select('product.id', 'product.product_name', 'inspection_detail.male_accepted_qty', 'inspection_detail.female_accepted_qty')
            ->get();
    
        return response()->json(['products' => $products, 'inspection' => $inspection]);
    }
    public function store(Request $request)
    {
        $inspection = Inspection::findOrFail($request->inspection_id);
    
        foreach ($request->products as $product_id => $productData) {
            $inspectionDetail = InspectionDetail::where('inspection_id', $inspection->id)
                ->where('product_id', $product_id)
                ->first();
    
            if ($inspectionDetail) {
                $inspectionDetail->update([
                    'death_male_qty' => $inspectionDetail->death_male_qty + $productData['death_male_qty'],
                    'death_female_qty' => $inspectionDetail->death_female_qty + $productData['death_female_qty'],
                ]);
            }
        }
    
        $totalDeathQty = InspectionDetail::where('inspection_id', $inspection->id)
            ->sum(DB::raw('death_male_qty + death_female_qty'));
    
        $inspection->update(['total_death_qty' => $totalDeathQty]);
    
        return redirect()->back()->with('success', 'Death details recorded successfully.');
    }
       
    public function getSuppliersByShipment(Request $request)
    {
        $shipment_id = $request->shipment_id;
    
        // Fetch suppliers linked to the inspection table for the given shipment_id
        $suppliers = Supplier::whereIn('id', function ($query) use ($shipment_id) {
            $query->select('supplier_id')
                  ->from('inspection')
                  ->where('shipment_id', $shipment_id);
        })->select('id', 'name')->get();
    
        return response()->json(['suppliers' => $suppliers]);
    }
    
    
    
    


}

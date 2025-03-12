<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\Supplier;
use App\Models\InspectionDetail;
use App\Models\Shipment;
use App\Models\DeathAnimalMaster;
use App\Models\DeathAnimalDetail;
use Illuminate\Support\Facades\Auth;


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


    public function store(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'date' => 'required|date',
            'shipment_no' => 'required|exists:shipment,id',
            'supplier_id' => 'required|exists:supplier,id',
            'inspection_id' => 'required|exists:inspection,id',
            'products' => 'required|array',
            'products.*.death_male_qty' => 'nullable|integer|min:0',
            'products.*.death_female_qty' => 'nullable|integer|min:0',
        ]);
    
        // Store Death Animal Master record
        $deathAnimalMaster = DeathAnimalMaster::create([
            'date' => $validated['date'],
            'shipment_id' => $validated['shipment_no'],
            'supplier_id' => $validated['supplier_id'],
            'inspection_id' => $validated['inspection_id'],
            'user_id' => Auth::id(),
            'store_id' => 1,
        ]);
    
        // Store Death Animal Details & Update Inspection Details
        foreach ($validated['products'] as $product_id => $product) {
            $deathMaleQty = $product['death_male_qty'] ?? 0;
            $deathFemaleQty = $product['death_female_qty'] ?? 0;
            $totalDeathQty = $deathMaleQty + $deathFemaleQty;
    
            // Store in Death Animal Detail Table
            DeathAnimalDetail::create([
                'death_animal_master_id' => $deathAnimalMaster->id,
                'product_id' => $product_id,
                'death_male_qty' => $deathMaleQty,
                'death_female_qty' => $deathFemaleQty,
                'total_death_qty' => $totalDeathQty,
            ]);
    
            // Debugging Log
            \Log::info('Updating InspectionDetail', [
                'inspection_id' => $validated['inspection_id'],
                'product_id' => $product_id,
                'death_male_qty' => $deathMaleQty,
                'death_female_qty' => $deathFemaleQty,
            ]);
    
            // Check if record exists before updating
            $inspectionDetail = InspectionDetail::where('inspection_id', $validated['inspection_id'])
                ->where('product_id', $product_id)
                ->first();
    
            if ($inspectionDetail) {
                // Update Inspection Detail Table
                $inspectionDetail->update([
                    'death_male_qty' => $inspectionDetail->death_male_qty + $deathMaleQty,
                    'death_female_qty' => $inspectionDetail->death_female_qty + $deathFemaleQty,
                ]);
    
                \Log::info("InspectionDetail updated successfully", [
                    'inspection_id' => $validated['inspection_id'],
                    'product_id' => $product_id,
                    'new_death_male_qty' => $inspectionDetail->death_male_qty,
                    'new_death_female_qty' => $inspectionDetail->death_female_qty,
                ]);
            } else {
                \Log::error("No matching InspectionDetail found for inspection_id: {$validated['inspection_id']}, product_id: {$product_id}");
            }
        }
    
        return redirect()->route('deathanimal.index')->with('success', 'Death Animal and Inspection records updated successfully.');
    }
    
    
    
public function index()
{
    $deathAnimals = DeathAnimalMaster::with(['shipment', 'supplier', 'inspection',])->get();
    return view('death-animal.index', compact('deathAnimals'));
}   
    
public function destroy($id)
{
    $deathAnimal = DeathAnimalMaster::findOrFail($id);
    
    // Fetch related DeathAnimalDetails
    $deathDetails = DeathAnimalDetail::where('death_animal_master_id', $id)->get();

    foreach ($deathDetails as $detail) {
        // Update Inspection Detail (Subtract death quantities)
        InspectionDetail::where('inspection_id', $deathAnimal->inspection_id)
            ->where('product_id', $detail->product_id)
            ->update([
                'death_male_qty' => \DB::raw("GREATEST(death_male_qty - {$detail->death_male_qty}, 0)"),
                'death_female_qty' => \DB::raw("GREATEST(death_female_qty - {$detail->death_female_qty}, 0)")
            ]);
    }

    // Delete Death Animal Details
    DeathAnimalDetail::where('death_animal_master_id', $id)->delete();

    // Delete Death Animal Master record
    $deathAnimal->delete();

    return redirect()->route('deathanimal.index')->with('success', 'Death Animal record deleted successfully.');
}


public function show($id)
{
    $deathAnimal = DeathAnimalMaster::with(['shipment', 'supplier', 'inspection', 'details'])->findOrFail($id);
    
    return view('death-animal.view', compact('deathAnimal'));
}



}

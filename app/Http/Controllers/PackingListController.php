<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth; 
use App\Models\PackingListMaster;
use App\Models\PackingListDetail;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;

class PackingListController extends Controller
{
    public function create()
    {
        $customers = Customer::all(); 
        $products = Product::all();
        $SalesOrders = SalesOrder::all();
        
       
        return view('packing_list.create',['invoice_no'=>$this->invoice_no()],compact('customers','products','SalesOrders'));
    }
    public function index()
{
    $packingLists = PackingListMaster::with('details')->orderBy('id', 'desc')->get();
    return view('packing_list.index', compact('packingLists'));
}

    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('packinglist_no',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

                 public function store(Request $request)
{
    $request->validate([
        'packing_no' => 'required',
        'date' => 'required|date',
        'salesOrder_id' => 'required|exists:sales_order,id',
      
        'net_weight' => 'required|numeric',
        'gross_weight' => 'required|numeric',
        'products' => 'required|array',
    ]);

    // Create PackingListMaster Entry
    $packingMaster = PackingListMaster::create([
        'packing_no' => $request->packing_no,
        'date' => $request->date,
        'salesOrder_id' => $request->salesOrder_id,
        'customer_id' => $request->customer_id,
        'shipping_mode' => $request->shipping_mode,
        'shipping_agent' => $request->shipping_agent,
        'terms_of_delivery' => $request->terms_of_delivery,
        'terms_of_payment' => $request->terms_of_payment,
        'currency' => $request->currency,
        'sum_total' => null,
        'net_weight' => $request->net_weight,
        'gross_weight' => $request->gross_weight,
        'store_id' => 1, 
        'user_id' => auth()->id(), 
    ]);
    InvoiceNumber::updateinvoiceNumber('packinglist_no', 1);
    // Insert Data into PackingListDetail
    foreach ($request->products as $product) {
        PackingListDetail::create([
            'packing_master_id' => $packingMaster->id,
            'product_id' => $product['product_id'],
            'packaging' => $product['packaging'],
            'weight' => $product['weight'],
            'par' => $product['par'],
            'total' => $product['total'],
        ]);
    }

    return redirect()->route('packinglist.index')->with('success', 'Sales Data Stored Successfully.');
}



public function edit($id)
{
    $packing = PackingListMaster::with('details')->findOrFail($id);
    $customers = Customer::all();
    $products = Product::all();
    $SalesOrders = SalesOrder::all();
    return view('packing_list.edit', compact('packing', 'customers', 'products','SalesOrders'));
}

public function update(Request $request, $id)
{
    // Validate the form inputs
    $request->validate([
        'packing_no' => 'required|string|max:255',
        'date' => 'required|date',
        'customer_id' => 'required|integer',
        'salesOrder_id' => 'required|integer',
        'shipping_mode' => 'nullable|string|max:255',
        'shipping_agent' => 'nullable|string|max:255',
        'terms_of_delivery' => 'nullable|string|max:255',
        'terms_of_payment' => 'nullable|string|max:255',
        'currency' => 'nullable|string|max:255',
        'sum_total' => 'nullable|numeric',
        'net_weight' => 'nullable|numeric',
        'gross_weight' => 'nullable|numeric',
        'products.*.product_id' => 'required|integer',
        'products.*.packaging' => 'nullable|numeric',
        'products.*.weight' => 'nullable|numeric',
        'products.*.par' => 'nullable|string',
        'products.*.total' => 'nullable|numeric',
    ]);

    // Find the packing list record
    $packingList = PackingListMaster::findOrFail($id);

    // Update the packing list details
    $packingList->update([
        'packing_no' => $request->packing_no,
        'date' => $request->date,
        'customer_id' => $request->customer_id,
        'salesOrder_id' => $request->salesOrder_id,
        'shipping_mode' => $request->shipping_mode,
        'shipping_agent' => $request->shipping_agent,
        'terms_of_delivery' => $request->terms_of_delivery,
        'terms_of_payment' => $request->terms_of_payment,
        'currency' => strtoupper($request->currency),
        'sum_total' => null,
        'net_weight' => $request->net_weight,
        'gross_weight' => $request->gross_weight,
    ]);

    // Delete old product details and insert new ones
    $packingList->details()->delete();

    foreach ($request->products as $product) {
        PackingListDetail::create([
            'packing_master_id' => $packingList->id, // Associate with the main record
            'product_id' => $product['product_id'],
            'packaging' => $product['packaging'],
            'weight' => $product['weight'],
            'par' => $product['par'],
            'total' => $product['total'],
        ]);
    }

    return redirect()->route('packinglist.index')->with('success', 'Packing List updated successfully!');
}



public function show($id)
{
    $packing = PackingListMaster::with('details')->findOrFail($id);
    return view('packing_list.show', compact('packing'));
}


public function destroy($id)
{
    try {
        $packing = PackingListMaster::findOrFail($id);

        
        $packing->details()->delete();

        
        $packing->delete();
        InvoiceNumber::decreaseInvoice('packinglist_no', 1);
        return redirect()->route('packinglist.index')->with('success', 'Packing list deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('packinglist.index')->with('error', 'Error deleting packing list: ' . $e->getMessage());
    }
}




public function packlistPrint($id)
{
    // Fetch the packing list master data along with customer and sales order
    $packingList = PackingListMaster::with(['customer', 'salesOrder', 'details.product'])
        ->join('customer', 'packing_list_masters.customer_id', '=', 'customer.id')
        ->select('packing_list_masters.*', 'customer.customer_code') // Fetch customer_code
        ->findOrFail($id);
        $totalPackaging = $packingList->details->sum('packaging');
        $totalWeight = $packingList->details->sum('weight');
       
        $totalAmount = $packingList->details->sum('total');

    return view('packing_list.print', compact( 'packingList', 'totalPackaging', 'totalWeight', 
    'totalAmount'));
}

public function pendingDelete()
{
    $pendingDeletes = PackingListMaster::where('delete_status', 1)->get();
    return view('packing_list.pending-delete', compact('pendingDeletes'));
}


public function requestDelete($id)
{
    try {
        $packing = PackingListMaster::findOrFail($id);
        $user = auth()->user();

        if ($user->designation_id == 3) {
            if ($packing->delete_status == 0) {
                $packing->delete_status = 1;
                $packing->save();

                return redirect()->route('packinglist.index')->with('success', 'Delete request sent for admin approval.');
            } else {
                return redirect()->route('packinglist.index')->with('info', 'This packing list has already been requested for deletion.');
            }
        } else {
            return redirect()->route('packinglist.index')->with('error', 'Unauthorized action.');
        }
    } catch (\Exception $e) {
        return redirect()->route('packinglist.index')->with('error', 'Error sending delete request: ' . $e->getMessage());
    }
}


public function editRequest($id)
{
    $packing = PackingListMaster::with('details')->findOrFail($id);
    $customers = Customer::all();
    $products = Product::all();
    $SalesOrders = SalesOrder::all();
    return view('packing_list.edit-request', compact('packing', 'customers', 'products','SalesOrders'));
}
public function submitEditRequest(Request $request, $id)
{
    $packingList = PackingListMaster::with('details')->findOrFail($id);

    $data = $request->validate([
        'packing_no' => 'required',
        'date' => 'required|date',
        'customer_id' => 'required',
        'salesOrder_id' => 'required',
        'shipping_mode' => 'nullable',
        'shipping_agent' => 'nullable',
        'terms_of_delivery' => 'nullable',
        'terms_of_payment' => 'nullable',
        'currency' => 'nullable',
        'net_weight' => 'required|numeric',
        'gross_weight' => 'required|numeric',
        'products' => 'required|array',
        'products.*.product_id' => 'required',
        'products.*.packaging' => 'nullable|numeric',
        'products.*.weight' => 'nullable|numeric',
        'products.*.par' => 'nullable',
        'products.*.total' => 'nullable|numeric',
    ]);

    // Only store changed main fields
    $editData = [];
    $mainFields = [
        'packing_no', 'date', 'customer_id', 'salesOrder_id', 'shipping_mode', 
        'shipping_agent', 'terms_of_delivery', 'terms_of_payment', 'currency',
        'net_weight', 'gross_weight'
    ];
    
    foreach ($mainFields as $field) {
        if (isset($data[$field])) {
            if ($packingList->$field != $data[$field]) {
                $editData[$field] = $data[$field];
            }
        }
    }

    $existingProducts = $packingList->details->keyBy('product_id');
    $productChanges = [];
    $submittedProductIds = [];

    foreach ($data['products'] as $newProduct) {
        $productId = $newProduct['product_id'];
        $submittedProductIds[] = $productId;

        if ($existingProducts->has($productId)) {
            $old = $existingProducts[$productId];
            $productChange = ['product_id' => $productId, 'old_product_id' => $productId];
            $hasChanges = false;

            foreach (['packaging', 'weight', 'par', 'total'] as $field) {
                if (isset($newProduct[$field]) && $old->$field != $newProduct[$field]) {
                    $productChange['old_'.$field] = $old->$field;
                    $productChange[$field] = $newProduct[$field];
                    $hasChanges = true;
                }
            }

            if ($hasChanges) {
                $productChanges[] = $productChange;
            }
        } else {
            // New product addition
            $productChanges[] = [
                'product_id' => $productId,
                'old_product_id' => null,
                'packaging' => $newProduct['packaging'] ?? null,
                'weight' => $newProduct['weight'] ?? null,
                'par' => $newProduct['par'] ?? null,
                'total' => $newProduct['total'] ?? null,
            ];
        }
    }

    // Check for deleted products
    foreach ($existingProducts as $productId => $detail) {
        if (!in_array($productId, $submittedProductIds)) {
            $productChanges[] = [
                'product_id' => null,
                'old_product_id' => $productId,
                'old_packaging' => $detail->packaging,
                'old_weight' => $detail->weight,
                'old_par' => $detail->par,
                'old_total' => $detail->total,
            ];
        }
    }

    $packingList->edit_request_data = json_encode([
        'main' => $editData,
        'products' => $productChanges,
    ]);

    $packingList->edit_status = 'pending';
    $packingList->save();

    ActionHistory::create([
        'page_name' => 'Packing List',
        'record_id' => $packingList->id . '-' . $packingList->packing_no,
        'action_type' => 'edit_requested',
        'user_id' => Auth::id(),
        'changes' => json_encode([
            'main' => $editData,
            'products' => $productChanges,
        ]),
    ]);

    return redirect()->route('packinglist.index')->with('success', 'Edit request submitted.');
}

public function approveEditRequest($id)
{
    $packingList = PackingListMaster::with('details')->findOrFail($id);

    if ($packingList->edit_status !== 'pending' || !$packingList->edit_request_data) {
        return back()->with('error', 'No pending edit request found or request already processed.');
    }

    $editData = json_decode($packingList->edit_request_data, true);

    DB::beginTransaction();
    try {
        // Update main packing list fields if they exist in the edit data
        if (isset($editData['main'])) {
            $packingList->update($editData['main']);
        }

        // Process product changes
        $processedDetailIds = [];
        $productChanges = $editData['products'] ?? [];

        foreach ($productChanges as $productEdit) {
            $productId = $productEdit['product_id'] ?? null;
            $oldProductId = $productEdit['old_product_id'] ?? $productId;

            // Handle product modifications and additions
            if ($productId !== null) {
                $existingDetail = $packingList->details->firstWhere('product_id', $oldProductId);

                if ($existingDetail) {
                    // Update existing product
                    $existingDetail->update([
                        'product_id' => $productId,
                        'packaging' => $productEdit['packaging'] ?? $existingDetail->packaging,
                        'weight' => $productEdit['weight'] ?? $existingDetail->weight,
                        'par' => $productEdit['par'] ?? $existingDetail->par,
                        'total' => $productEdit['total'] ?? $existingDetail->total,
                    ]);
                    $processedDetailIds[] = $existingDetail->id;
                } else {
                    // Add new product
                    $newDetail = PackingListDetail::create([
                        'packing_master_id' => $packingList->id,
                        'product_id' => $productId,
                        'packaging' => $productEdit['packaging'] ?? null,
                        'weight' => $productEdit['weight'] ?? null,
                        'par' => $productEdit['par'] ?? null,
                        'total' => $productEdit['total'] ?? null,
                    ]);
                    $processedDetailIds[] = $newDetail->id;
                }
            } else {
                // Handle product deletions (where product_id is null)
                $packingList->details()
                    ->where('product_id', $oldProductId)
                    ->delete();
            }
        }

        // Clean up and finalize
        $packingList->edit_status = 'approved';
        $packingList->edit_request_data = null;
        $packingList->save();

        // Log the approval
        ActionHistory::create([
            'page_name' => 'Packing List',
            'record_id' => $packingList->id . '-' . $packingList->packing_no,
            'action_type' => 'edit_approved',
            'user_id' => Auth::id(),
            'changes' => json_encode($editData),
        ]);

        DB::commit();

        return redirect()->route('packinglist.index')
            ->with('success', 'Edit request approved successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error approving edit request: ' . $e->getMessage());
        
        return back()->with('error', 'Failed to approve edit request. Please try again.');
    }
}

public function rejectEditRequest($id)
{
    $packingList = PackingListMaster::findOrFail($id);

    if ($packingList->edit_status !== 'pending') {
        return back()->with('error', 'No pending edit request found or request already processed.');
    }

    $packingList->edit_status = 'rejected';
    $packingList->edit_request_data = null;
    $packingList->save();

    ActionHistory::create([
        'page_name' => 'Packing List',
        'record_id' => $packingList->id . '-' . $packingList->packing_no,
        'action_type' => 'edit_rejected',
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('packinglist.index')
        ->with('success', 'Edit request rejected successfully.');
}

public function pendingEditRequests()
{
    $packingLists = PackingListMaster::with(['details', 'customer', 'salesOrder'])
        ->where('edit_status', 'pending')
        ->get();

    foreach ($packingLists as $packingList) {
        $editData = json_decode($packingList->edit_request_data, true);
        $changes = [];

        if ($editData) {
            // Main fields
            if (isset($editData['main'])) {
                foreach ($editData['main'] as $key => $newVal) {
                    $changes[$key] = [
                        'original' => $packingList->$key,
                        'requested' => $newVal,
                    ];
                }
            }

            // Product changes
            if (isset($editData['products'])) {
                $changes['products'] = [];

                foreach ($editData['products'] as $productEdit) {
                    $productChanges = [];

                    $productId = $productEdit['product_id'] ?? null;
                    $oldProductId = $productEdit['old_product_id'] ?? null;

                    // Only include product_id if there was a product change
                    if ($oldProductId != $productId) {
                        $productChanges['product_id'] = [
                            'original' => $oldProductId,
                            'requested' => $productId,
                        ];
                    } else {
                        // Include original/current product ID for context
                        $productChanges['product_id'] = $productId;
                    }

                    // Handle packaging, weight, par, total changes
                    foreach (['packaging', 'weight', 'par', 'total'] as $field) {
                        if (
                            isset($productEdit[$field]) &&
                            (!isset($productEdit['old_' . $field]) || $productEdit[$field] != $productEdit['old_' . $field])
                        ) {
                            $productChanges[$field] = [
                                'original' => $productEdit['old_' . $field] ?? null,
                                'requested' => $productEdit[$field] ?? null,
                            ];
                        }
                    }

                    if (!empty($productChanges)) {
                        $changes['products'][] = $productChanges;
                    }
                }
            }

            $packingList->changed_fields = $changes;
        }
    }

    return view('packing_list.pending-edit-request', compact('packingLists'));
}


 
}

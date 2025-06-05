<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\InvoiceNumber;
use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SalesOrder;
use App\Models\Outstanding;
use Illuminate\Support\Facades\Log;
use App\Models\ActionHistory;


class PurchaseOrderController extends Controller
{

    // public function index()
    // {
    //     $purchaseOrders = PurchaseOrder::with(['supplier', 'details', 'salesOrder', 'shipment'])
    //         ->whereHas('shipment', function ($query) {
    //             $query->where('shipment_status', 0);
    //         })
    //         ->orderBy('id', 'desc')
    //         ->get();
    //         $totalAdvance = $purchaseOrders->sum('advance_amount');
    
    //     return view('purchase-order.index', compact('purchaseOrders','totalAdvance'));
    // }
public function index(Request $request)
{
    $supplierId = $request->get('supplier_id');
    $query = PurchaseOrder::with(['supplier', 'shipment', 'salesOrder', 'details', 'user'])
     ->whereHas('shipment', function ($query) {
                $query->where('shipment_status', 0);
             });

    if ($supplierId) {
        $query->where('supplier_id', $supplierId);
    }

    $purchaseOrders = $query->get();

    $suppliers = Supplier::all();

    // Total Advance and Quantity (Animals Count)
    $totalAdvance = $purchaseOrders->sum('advance_amount');
    $totalQuantity = $purchaseOrders->sum(function ($order) {
        return $order->details->sum('qty');
    });

    return view('purchase-order.index', compact(
        'purchaseOrders',
        'suppliers',
        'supplierId',
        'totalAdvance',
        'totalQuantity'
    ));
}





  
        public function create()
        {
            $supplierOutstandingBalances = Outstanding::where('account_type', 'supplier')
            ->select('account_id', DB::raw('SUM(payment) as total_payment, SUM(receipt) as total_receipt'))
            ->groupBy('account_id')
            ->get()
            ->mapWithKeys(function($item) {
                $balance = $item->total_payment - $item->total_receipt;
                return [$item->account_id => number_format($balance, 2, '.', '')];
            });
            $suppliers = Supplier::all(); 
            $products = Product::all();
            $SalesOrders=SalesOrder::all();
            $shipments = Shipment::where('shipment_status', 0)->get();
            return view('purchase-order.create',['invoice_no'=>$this->invoice_no()],compact('suppliers','products','shipments','SalesOrders','supplierOutstandingBalances'));
        }
    
        public function invoice_no(){
            try {
                 
             return $invoice_no =  InvoiceNumber::ReturnInvoice('purchase_order',1);
                      } catch (\Exception $e) {
             
                return $e->getMessage();
              }
                     }
    
                    
                     
                     public function store(Request $request)
                     {
                        // return $request->all();
                         $request->validate([
                             'order_no' => 'required|unique:purchase_order,order_no',
                             'date' => 'required|date',
                             'supplier_id' => 'required|exists:supplier,id',
                             'shipment_id'=> 'required|exists:shipment,id',
                             'SalesOrder_id' => 'required|exists:sales_order,id',
                         ]);
                     
                         DB::beginTransaction();
                         try {
                             $supplier = Supplier::find($request->supplier_id);
                     
                             // Calculate the credit limit expiration date
                            //  if ($supplier->credit_limit_days > 0) {
                            //      $credit_limit_date = Carbon::parse($supplier->created_at)->addDays($supplier->credit_limit_days);
                     
                            //      // Check if the sales order date exceeds the credit limit date
                            //      if (Carbon::parse($request->date)->greaterThan($credit_limit_date)) {
                            //          return redirect()->back()->withErrors(['error' => 'Credit limit days have expired for this supplier.']);
                            //      }
                            //  }
                     
                             // Store the purchase order
                             $purchaseOrder = PurchaseOrder::create([
                                 'order_no' => $request->order_no,
                                 'date' => $request->date,
                                 'supplier_id' => $request->supplier_id,
                                 'grand_total' => 0,
                                 'advance_amount' => isset($request->advance_amount) ? (float) str_replace(',', '', $request->advance_amount) : 0,
                                 'balance_amount' => 0,
                                 'store_id' => 1,
                                 'user_id' => auth()->id(),
                                 'status' => 1,
                                 'inspection_status' => 0,
                                 'shipment_id' =>$request->shipment_id,
                                 'SalesOrder_id' => $request->SalesOrder_id,
                             ]);
                     
                             // Store the purchase order details
                             foreach ($request->products as $product) {
                                 PurchaseOrderDetail::create([
                                     'purchase_order_id' => $purchaseOrder->id,
                                     'product_id' => $product['product_id'],
                                     'type' => null,
                                     'qty' => $product['qty'],
                                     'male' => $product['male'],
                                     'female' => $product['female'],
                                     'rate' => 0,
                                     'total' => 0,
                                     'store_id' => 1,
                                 ]);
                             }
                     
                             InvoiceNumber::updateinvoiceNumber('purchase_order', 1);
                     
                             DB::commit();
                             return redirect()->route('purchase-order.index')->with('success', 'Purchase Order Created Successfully');
                         } catch (\Exception $e) {
                             DB::rollback();
                             return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                         }
                     }
    
    
        public function edit($id)
        {
            $purchaseOrder = PurchaseOrder::with('details','products')->findOrFail($id);
            $suppliers = Supplier::all();
            $products = Product::all();
            $SalesOrders=SalesOrder::all();
           
            $shipments = Shipment::where('shipment_status', 0)->get();
            return view('purchase-order.edit', compact('purchaseOrder', 'suppliers', 'products','SalesOrders','shipments'));
        }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_no' => 'required',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'SalesOrder_id' => 'required|exists:sales_order,id',
            // 'grand_total' => 'nu|numeric|min:0',
        ]);
    
        try {
            // Update the sales order
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $purchaseOrder->update([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'grand_total' => 0,
                'advance_amount' => $request->advance_amount ?? 0,
                'balance_amount' =>  0,
                'SalesOrder_id' => $request->SalesOrder_id,
                'shipment_id' =>$request->shipment_id,
            ]);
    
            // Clear old sales order details and re-insert updated ones
            PurchaseOrderDetail::where('purchase_order_id', $id)->delete();
    
            foreach ($request->products as $product) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product['product_id'],
                    'type' => null ,
                    'qty' => $product['qty'],
                    'male' => $product['male'] ,
                    'female' => $product['female'],
                    'rate' => 0,
                    'total' => 0,
                    'store_id' => Auth::user()->store_id ?? 1,  // Ensure store_id is set here
                    'user_id' => Auth::id(), // Ensure user_id is set here
                ]);
            }
    
            return redirect()->route('purchase-order.index')->with('success', 'purchase Order updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function view($id)
    {
        $purchaseOrder = PurchaseOrder::with('supplier','details')->findOrFail($id);
    
        return view('purchase-order.view', compact('purchaseOrder'));
    }
    
    
    
    
    public function destroy($id)
    {
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);
    
          
            $purchaseOrder->details()->delete();
    
            
            $purchaseOrder->delete();
          
    
            return redirect()->route('purchase-order.index')->with('success', 'purchase order and its details have been deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('purchase-order.index')->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }


    public function report(Request $request)
{
    $query = PurchaseOrder::with('supplier');

    
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    $purchaseOrders = $query->get();
    $suppliers = Supplier::all();

    return view('purchase-order.report', compact('purchaseOrders', 'suppliers'));
}


public function getOutstandingBalance($supplierId)
{
    try {
        $outstanding = Outstanding::where('account_id', $supplierId)
            ->selectRaw('SUM(payment) as total_payment, SUM(receipt) as total_receipt')
            ->first();

        if ($outstanding) {
            $balance = $outstanding->total_payment - $outstanding->total_receipt;
            return response()->json(['balance' => number_format($balance, 2)]);
        } else {
            return response()->json(['balance' => '0.00']);
        }
    } catch (\Exception $e) {
        Log::error('Error fetching outstanding balance: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}

public function softDelete($id)
{
    $po = PurchaseOrder::findOrFail($id);
    $po->delete_status = 1;
    $po->save();

    ActionHistory::create([
        'page_name'   => 'Purchase Order',
        'record_id'   => $po->id . '-' . $po->order_no,
        'action_type' => 'delete_requested',
        'user_id'     => Auth::id(),
        'changes'     => null,
    ]);

    return redirect()->route('purchase-order.index')->with('success', 'Purchase Order marked for deletion.');
}


public function pendingDeleteRequests()
{
    $requests = PurchaseOrder::where('delete_status', 1)->with('supplier', 'shipment', 'salesOrder', 'user')->get();
    return view('purchase-order.pending-delete', compact('requests'));
}

public function adminDelete($id)
{
    $po = PurchaseOrder::where('delete_status', 1)->findOrFail($id);

    try {
        ActionHistory::create([
            'page_name'   => 'Purchase Order',
            'record_id'   => $po->id . '-' . $po->order_no,
            'action_type' => 'delete_approved',
            'user_id'     => Auth::id(),
            'changes'     => null,
        ]);
    } catch (\Exception $e) {
        \Log::error('ActionHistory insert failed: ' . $e->getMessage());
    }

    // Delete related details
    $po->details()->delete();

    // Delete the PO itself
    $po->delete();

    return redirect()->route('admin.purchaseorder.admindelete')->with('success', 'PO permanently deleted.');
}



   public function editRequest($id)
        {
            $purchaseOrder = PurchaseOrder::with('details','products')->findOrFail($id);
            $suppliers = Supplier::all();
            $products = Product::all();
            $SalesOrders=SalesOrder::all();
           
            $shipments = Shipment::where('shipment_status', 0)->get();
            return view('purchase-order.edit-request', compact('purchaseOrder', 'suppliers', 'products','SalesOrders','shipments'));
        }
public function submitEditRequest(Request $request, $id)
{
    $po = PurchaseOrder::with('details')->findOrFail($id);

    $data = $request->validate([
        'order_no' => 'required',
        'date' => 'required|date',
        'supplier_id' => 'required',
        'shipment_id' => 'required',
        'SalesOrder_id' => 'required',
        'advance_amount' => 'nullable|numeric',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.qty' => 'required|numeric',
        'products.*.male' => 'nullable|numeric',
        'products.*.female' => 'nullable|numeric',
    ]);

    // Only store changed main fields
    $editData = [];
    foreach (['order_no', 'date', 'supplier_id', 'shipment_id', 'SalesOrder_id', 'advance_amount'] as $field) {
        if (isset($data[$field]) && $po->$field != $data[$field]) {
            $editData[$field] = $data[$field];
        }
    }

    $existingProducts = $po->details->keyBy('product_id');
    $productChanges = [];
    $submittedProductIds = [];

    foreach ($data['products'] as $newProduct) {
        $productId = $newProduct['product_id'];
        $submittedProductIds[] = $productId;

        if ($existingProducts->has($productId)) {
            $old = $existingProducts[$productId];
            $productChange = ['product_id' => $productId, 'old_product_id' => $productId];
            $hasChanges = false;

            foreach (['qty', 'male', 'female'] as $field) {
                if ($old->$field != $newProduct[$field]) {
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
                'qty' => $newProduct['qty'],
                'male' => $newProduct['male'],
                'female' => $newProduct['female'],
            ];
        }
    }

    // Check for deleted products
    foreach ($existingProducts as $productId => $detail) {
        if (!in_array($productId, $submittedProductIds)) {
            $productChanges[] = [
                'product_id' => null,
                'old_product_id' => $productId,
                'old_qty' => $detail->qty,
                'old_male' => $detail->male,
                'old_female' => $detail->female,
            ];
        }
    }

    $po->edit_request_data = json_encode([
        'main' => $editData,
        'products' => $productChanges,
    ]);

    $po->edit_status = 'pending';
    $po->save();

    ActionHistory::create([
        'page_name' => 'Purchase Order',
        'record_id' => $po->id . '-' . $po->order_no,
        'action_type' => 'edit_requested',
        'user_id' => Auth::id(),
        'changes' => json_encode([
            'main' => $editData,
            'products' => $productChanges,
        ]),
    ]);

    return redirect()->route('purchase-order.index')->with('success', 'Edit request submitted.');
}

public function approveEditRequest($id)
{
    $po = PurchaseOrder::with('details')->findOrFail($id);

    if ($po->edit_status !== 'pending' || !$po->edit_request_data) {
        return back()->with('error', 'No pending edit request found or request already processed.');
    }

    $editData = json_decode($po->edit_request_data, true);

    DB::beginTransaction();
    try {
        // Update main order fields if they exist in the edit data
        if (isset($editData['main'])) {
            $po->update($editData['main']);
        }

        // Process product changes
        $processedDetailIds = [];
        $productChanges = $editData['products'] ?? [];

        foreach ($productChanges as $productEdit) {
            $productId = $productEdit['product_id'] ?? null;
            $oldProductId = $productEdit['old_product_id'] ?? $productId;

            // Handle product modifications and additions
            if ($productId !== null) {
                $existingDetail = $po->details->firstWhere('product_id', $oldProductId);

                if ($existingDetail) {
                    // Update existing product
                    $existingDetail->update([
                        'product_id' => $productId,
                        'qty' => $productEdit['qty'] ?? $existingDetail->qty,
                        'male' => $productEdit['male'] ?? $existingDetail->male,
                        'female' => $productEdit['female'] ?? $existingDetail->female,
                    ]);
                    $processedDetailIds[] = $existingDetail->id;
                } else {
                    // Add new product
                    $newDetail = PurchaseOrderDetail::create([
                        'purchase_order_id' => $po->id,
                        'product_id' => $productId,
                        'qty' => $productEdit['qty'] ?? 0,
                        'male' => $productEdit['male'] ?? 0,
                        'female' => $productEdit['female'] ?? 0,
                        'user_id' => Auth::id(),
                        'store_id' =>1
                    ]);
                    $processedDetailIds[] = $newDetail->id;
                }
            } else {
                // Handle product deletions (where product_id is null)
                $po->details()
                    ->where('product_id', $oldProductId)
                    ->delete();
            }
        }

        // Clean up and finalize
        $po->edit_status = 'approved';
        $po->edit_request_data = null;
        $po->save();

        // Log the approval
        ActionHistory::create([
            'page_name' => 'Purchase Order',
            'record_id' => $po->id . '-' . $po->order_no,
            'action_type' => 'edit_approved',
            'user_id' => Auth::id(),
            'changes' => json_encode($editData),
        ]);

        DB::commit();

        return redirect()->route('purchase-order.index')
            ->with('success', 'Edit request approved successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error approving edit request: ' . $e->getMessage());
        
        return back()->with('error', 'Failed to approve edit request. Please try again.');
    }
}
public function pendingEditRequests()
{
    $orders = PurchaseOrder::with(['details', 'supplier'])
        ->where('edit_status', 'pending')
        ->get();

    foreach ($orders as $order) {
        $editData = json_decode($order->edit_request_data, true);
        $changes = [];

        if ($editData) {
            // Main field changes
            if (isset($editData['main'])) {
                foreach ($editData['main'] as $key => $newVal) {
                    $changes[$key] = [
                        'original' => $order->$key,
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
                    
                    // Handle product ID changes (additions/deletions/modifications)
                    if ($oldProductId != $productId) {
                        $productChanges['product_id'] = [
                            'original' => $oldProductId,
                            'requested' => $productId,
                        ];
                    }
                    
                    // Handle quantity, male, female changes
                    foreach (['qty', 'male', 'female'] as $field) {
                        if (isset($productEdit[$field]) || isset($productEdit['old_'.$field])) {
                            $productChanges[$field] = [
                                'original' => $productEdit['old_'.$field] ?? null,
                                'requested' => $productEdit[$field] ?? null,
                            ];
                        }
                    }
                    
                    if (!empty($productChanges)) {
                        $changes['products'][] = $productChanges;
                    }
                }
            }

            $order->changed_fields = $changes;
        }
    }

    return view('purchase-order.pending-edit-request', compact('orders'));
}


public function rejectEdit($id)
{
    $po = PurchaseOrder::findOrFail($id);

    if ($po->edit_status === 'pending') {
        $editData = json_decode($po->edit_request_data, true);
        $po->edit_status = 'rejected';
        $po->save();

        $changes = [];

        if ($editData) {
            $original = $po->getOriginal();

            foreach ($editData as $key => $newVal) {
                $oldVal = $original[$key] ?? null;
                if ($oldVal != $newVal) {
                    $changes[$key] = ['old' => $oldVal, 'new' => $newVal];
                }
            }
        }

        ActionHistory::create([
            'page_name' => 'Purchase Order',
            'record_id' => $po->id . '-' . $po->order_number,
            'action_type' => 'edit_rejected',
            'user_id' => Auth::id(),
            'changes' => !empty($changes) ? json_encode($changes) : null,
        ]);
    }

    return redirect()->back()->with('success', 'Edit request rejected.');
}


    
}

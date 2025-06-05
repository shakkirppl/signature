<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\SalesOrderDetail;
use Carbon\Carbon;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;


class SalesOrderController extends Controller
{

    public function index()
{
    $salesOrders = SalesOrder::with('customer')->get();
    return view('sales-order.index', compact('salesOrders'));
}
    
    public function create()
    {
        $customers = Customer::all(); 
        $products = Product::all();
       
        return view('sales-order.create',['invoice_no'=>$this->invoice_no()],compact('customers','products'));
    }

    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('sales_order',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }


                 public function store(Request $request)
                 {
                     $request->validate([
                         'order_no' => 'required|unique:sales_order,order_no',
                         'date' => 'required|date',
                         'customer_id' => 'required|exists:customer,id',
                         'grand_total' => 'required|numeric|min:0',
                         'products.*.qty' => 'required|numeric|min:0.01',
                     ]);
                 
                     try {
                        

                         // Fetch the customer details
                         $customer = Customer::find($request->customer_id);
                 
                         // Calculate the credit limit expiration date
                        //  if ($customer->credit_limit_days > 0) {
                        //      $credit_limit_date = Carbon::parse($customer->created_at)->addDays($customer->credit_limit_days);
                 
                        //      // Check if the sales order date exceeds the credit limit date
                        //      if (Carbon::parse($request->date)->greaterThan($credit_limit_date)) {
                        //          return redirect()->back()->withErrors(['error' => 'Credit limit days have expired for this customer.']);
                        //      }
                        //  }
                 
                         // Store the sales order
                         $salesOrder = SalesOrder::create([
                             'order_no' => $request->order_no,
                             'date' => $request->date,
                             'customer_id' => $request->customer_id,
                             'grand_total' => $request->grand_total,
                             'advance_amount' => $request->advance_amount ?? 0,
                             'balance_amount' => $request->balance_amount ?? 0,
                             'store_id' => 1, 
                             'user_id' => auth()->id(), 
                         ]);
                 
                         // Store the sales order details
                         foreach ($request->products as $product) {
                             SalesOrderDetail::create([
                                 'sales_order_id' => $salesOrder->id,
                                 'product_id' => $product['product_id'],
                                 'qty' => $product['qty'],
                                 'rate' => $product['rate'],
                                 'total' => $product['total'],
                                 'store_id' => 1, 
                             ]);
                         }
                         InvoiceNumber::updateinvoiceNumber('sales_order',1);
                 
                         return redirect()->route('goodsout-order.index')->with('success', 'Sales Order created successfully.');
                     } catch (\Exception $e) {
                         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                     }
                 }
                 


    public function edit($id)
{
    $salesOrder = SalesOrder::with('details')->findOrFail($id);
    $customers = Customer::all();
    $products = Product::all();
    return view('sales-order.edit', compact('salesOrder', 'customers', 'products'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'order_no' => 'required',
        'date' => 'required|date',
        'customer_id' => 'required|exists:customer,id',
        'grand_total' => 'required|numeric|min:0',
        'products.*.qty' => 'required|numeric|min:0.01',
    ]);

    try {
        // Update the sales order
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->update([
            'date' => $request->date,
            'customer_id' => $request->customer_id,
            'grand_total' => $request->grand_total,
            'advance_amount' => $request->advance_amount ?? 0,
            'balance_amount' => $request->balance_amount ?? 0,
        ]);

        // Clear old sales order details and re-insert updated ones
        SalesOrderDetail::where('sales_order_id', $id)->delete();

        foreach ($request->products as $product) {
            SalesOrderDetail::create([
                'sales_order_id' => $salesOrder->id,
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'rate' => $product['rate'],
                'total' => $product['total'],
                'store_id' => Auth::user()->store_id ?? 1,  // Ensure store_id is set here
                'user_id' => Auth::id(), // Ensure user_id is set here
            ]);
        }

        return redirect()->route('goodsout-order.index')->with('success', 'Sales Order updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function view($id)
{
    $salesOrder = SalesOrder::with('customer','details')->findOrFail($id);

    return view('sales-order.view', compact('salesOrder'));
}




public function destroy($id)
{
    try {
        $salesOrder = SalesOrder::findOrFail($id);

      
        $salesOrder->details()->delete();

        
        $salesOrder->delete();
        InvoiceNumber::decreaseInvoice('sales_order', 1);

        return redirect()->route('goodsout-order.index')->with('success', 'Sales order and its details have been deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('goodsout-order.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}


public function report(Request $request)
{
    $customers = Customer::all();

   
    $query = SalesOrder::with('customer');

    if ($request->customer_id) {
        $query->where('customer_id', $request->customer_id);
    }

    if ($request->from_date) {
        $query->whereDate('date', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('date', '<=', $request->to_date);
    }

    $salesOrders = $query->get();

    return view('sales-order.report', compact('salesOrders', 'customers'));
}
// public function requestDelete($id)
// {
//     try {
//         $order = SalesOrder::findOrFail($id);

//         if (auth()->user()->designation_id != 3) {
//             abort(403);
//         }

//         $order->delete_status = '1';
//         $order->save();

//         return redirect()->route('goodsout-order.index')->with('success', 'Delete request submitted.');
//     } catch (\Exception $e) {
//         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
//     }
// }
public function softDelete($id)
{
    $order = SalesOrder::findOrFail($id);
    $order->delete_status = 1;
    $order->save();

    ActionHistory::create([
        'page_name'   => 'Sales Order',
        'record_id'   => $order->id . '-' . $order->order_no,
        'action_type' => 'delete_requested',
        'user_id'     => Auth::id(),
        'changes'     => null,
    ]);

    return redirect()->route('goodsout-order.index')->with('success', 'Sales Order marked for deletion.');
}


public function pendingDeleteRequests()
{
    $orders = SalesOrder::where('delete_status', 1)->with('customer')->get();
    return view('sales-order.pending-delete', compact('orders'));
}

public function adminDelete($id)
{
    $order = SalesOrder::where('delete_status', 1)->findOrFail($id);

    ActionHistory::create([
        'page_name'   => 'Sales Order',
        'record_id'   => $order->id . '-' . $order->order_no,
        'action_type' => 'delete_approved',
        'user_id'     => Auth::id(),
        'changes'     => null,
    ]);

    $order->details()->delete();
    $order->delete();

    return redirect()->route('admin.salesorder.admindelete')->with('success', 'Sales Order permanently deleted.');
}


public function editRequest($id)
{
    $order = SalesOrder::with('details')->findOrFail($id);
    $customers = Customer::all();
    $products = Product::all();
    return view('sales-order.edit-request', compact('order', 'customers', 'products'));
}


public function submitEditRequest(Request $request, $id)
{
    $order = SalesOrder::with('details')->findOrFail($id);

    $data = $request->validate([
        'order_no' => 'required',
        'date' => 'required|date',
        'customer_id' => 'required',
        'grand_total' => 'required|numeric',
        'advance_amount' => 'nullable|numeric',
        'balance_amount' => 'nullable|numeric',
        'products' => 'required|array',
        'products.*.product_id' => 'required',
        'products.*.qty' => 'required|numeric',
        'products.*.rate' => 'required|numeric',
        'products.*.total' => 'required|numeric',
    ]);

    // Only store changed main fields
    $editData = [];
    foreach (['order_no', 'date', 'customer_id', 'grand_total', 'advance_amount', 'balance_amount'] as $field) {
        if (isset($data[$field]) && $order->$field != $data[$field]) {
            $editData[$field] = $data[$field];
        }
    }

    $existingProducts = $order->details->keyBy('product_id');
    $productChanges = [];
    $submittedProductIds = [];

    foreach ($data['products'] as $newProduct) {
        $productId = $newProduct['product_id'];
        $submittedProductIds[] = $productId;

        if ($existingProducts->has($productId)) {
            $old = $existingProducts[$productId];
            $productChange = ['product_id' => $productId, 'old_product_id' => $productId];
            $hasChanges = false;

            foreach (['qty', 'rate', 'total'] as $field) {
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
                'rate' => $newProduct['rate'],
                'total' => $newProduct['total'],
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
                'old_rate' => $detail->rate,
                'old_total' => $detail->total,
            ];
        }
    }

    $order->edit_request_data = json_encode([
        'main' => $editData,
        'products' => $productChanges,
    ]);

    $order->edit_status = 'pending';
    $order->save();

    ActionHistory::create([
        'page_name' => 'Sales Order',
        'record_id' => $order->id . '-' . $order->order_no,
        'action_type' => 'edit_requested',
        'user_id' => Auth::id(),
        'changes' => json_encode([
            'main' => $editData,
            'products' => $productChanges,
        ]),
    ]);

    return redirect()->route('goodsout-order.index')->with('success', 'Edit request submitted.');
}




public function approveEditRequest($id)
{
    $order = SalesOrder::with('details')->findOrFail($id);

    if ($order->edit_status !== 'pending' || !$order->edit_request_data) {
        return back()->with('error', 'No pending edit request found or request already processed.');
    }

    $editData = json_decode($order->edit_request_data, true);

    DB::beginTransaction();
    try {
        // Update main order fields if they exist in the edit data
        if (isset($editData['main'])) {
            $order->update($editData['main']);
        }

        // Process product changes
        $processedDetailIds = [];
        $productChanges = $editData['products'] ?? [];

        foreach ($productChanges as $productEdit) {
            $productId = $productEdit['product_id'] ?? null;
            $oldProductId = $productEdit['old_product_id'] ?? $productId;

            // Handle product modifications and additions
            if ($productId !== null) {
                $existingDetail = $order->details->firstWhere('product_id', $oldProductId);

                if ($existingDetail) {
                    // Update existing product
                    $existingDetail->update([
                        'product_id' => $productId,
                        'qty' => $productEdit['qty'] ?? $existingDetail->qty,
                        'rate' => $productEdit['rate'] ?? $existingDetail->rate,
                        'total' => $productEdit['total'] ?? $existingDetail->total,
                    ]);
                    $processedDetailIds[] = $existingDetail->id;
                } else {
                    // Add new product
                    $newDetail = SalesOrderDetail::create([
                        'sales_order_id' => $order->id,
                        'product_id' => $productId,
                        'qty' => $productEdit['qty'] ?? 0,
                        'rate' => $productEdit['rate'] ?? 0,
                        'total' => $productEdit['total'] ?? 0,
                        'store_id' => 1, // Assuming default store
                        'user_id' => Auth::id(),
                    ]);
                    $processedDetailIds[] = $newDetail->id;
                }
            } else {
                // Handle product deletions (where product_id is null)
                $order->details()
                    ->where('product_id', $oldProductId)
                    ->delete();
            }
        }

        // Clean up and finalize
        $order->edit_status = 'approved';
        $order->edit_request_data = null;
        $order->save();

        // Log the approval
        ActionHistory::create([
            'page_name' => 'Sales Order',
            'record_id' => $order->id . '-' . $order->order_no,
            'action_type' => 'edit_approved',
            'user_id' => Auth::id(),
            'changes' => json_encode($editData),
        ]);

        DB::commit();

        return redirect()->route('goodsout-order.index')
            ->with('success', 'Edit request approved successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error approving edit request: ' . $e->getMessage());
        
        return back()->with('error', 'Failed to approve edit request. Please try again.');
    }
}
public function pendingEditRequests()
{
    $orders = SalesOrder::with(['details', 'customer'])
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

                    // Always include product_id for context
                    $productChanges['product_id'] = [
                        'original' => $oldProductId ?? $productId,
                        'requested' => $productId ?? $oldProductId,
                    ];

                    // Handle qty, rate, total changes
                    foreach (['qty', 'rate', 'total'] as $field) {
                        if (isset($productEdit[$field]) || isset($productEdit['old_' . $field])) {
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

            $order->changed_fields = $changes;
        }
    }

    return view('sales-order.pending-edit-request', compact('orders'));
}



public function rejectEdit($id)
{
    $order = SalesOrder::findOrFail($id);
    if ($order->edit_status === 'pending') {
        $editData = json_decode($order->edit_request_data, true);
        $order->edit_status = 'rejected';
        $order->save();

        ActionHistory::create([
            'page_name' => 'Sales Order',
            'record_id' => $order->id . '-' . $order->order_no,
            'action_type' => 'edit_rejected',
            'user_id' => Auth::id(),
            'changes' => json_encode($editData),
        ]);
    }

    return redirect()->back()->with('success', 'Edit request rejected.');
}








}

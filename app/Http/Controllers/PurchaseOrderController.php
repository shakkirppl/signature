<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
{
    $purchaseOrders = PurchaseOrder::with(['supplier', 'details'])->get();
    return view('purchase-order.index', compact('purchaseOrders'));
}
  
        public function create()
        {
            $suppliers = \App\Models\Supplier::all(); 
            $products = Product::all();
           
            return view('purchase-order.create',['invoice_no'=>$this->invoice_no()],compact('suppliers','products'));
        }
    
        public function invoice_no(){
            try {
                 
             return $invoice_no =  InvoiceNumber::ReturnInvoice('purchase_order',Auth::user()->store_id=1);
                      } catch (\Exception $e) {
             
                return $e->getMessage();
              }
                     }
    
                     public function store(Request $request)
                     {
                         $request->validate([
                             'order_no' => 'required|unique:purchase_order,order_no',
                             'date' => 'required|date',
                             'supplier_id' => 'required|exists:supplier,id',
                             'grand_total' => 'required|numeric|min:0',
                         ]);
                     
                         try {
                            InvoiceNumber::updateinvoiceNumber('purchase_order',1);

                             // Get the supplier's credit limit days
                             $supplier = Supplier::find($request->supplier_id);
                             if (!$supplier) {
                                 return redirect()->back()->withErrors(['error' => 'Supplier not found.']);
                             }
                     
                             // Ensure credit_limit_days is numeric and not null
                             $credit_limit_days = is_numeric($supplier->credit_limit_days) ? (int) $supplier->credit_limit_days : 0;
                     
                             // Validate date and calculate the credit limit expiry date
                             if ($credit_limit_days > 0) {
                                 $credit_limit_date = \Carbon\Carbon::parse($request->date)->subDays($credit_limit_days);
                                 $current_date = \Carbon\Carbon::now();
                     
                                 // Check if the supplier's credit limit period has expired
                                 if ($credit_limit_date->lt($current_date)) {
                                     return redirect()->back()->withErrors(['error' => 'Credit limit days have expired for this supplier.']);
                                 }
                             }
                     
                             // Store the purchase order
                             $purchaseOrder = PurchaseOrder::create([
                                 'order_no' => $request->order_no,
                                 'date' => $request->date,
                                 'supplier_id' => $request->supplier_id,
                                 'grand_total' => $request->grand_total,
                                 'advance_amount' => $request->advance_amount ?? 0,
                                 'balance_amount' => $request->balance_amount ?? 0,
                                 'store_id' => 1, 
                                 'user_id' => auth()->id(), 
                                 'status' => 1,
                                 'inspection_status' => 0,
                             ]);
                     
                             // Store the purchase order details
                             foreach ($request->products as $product) {
                                 PurchaseOrderDetail::create([
                                     'purchase_order_id' => $purchaseOrder->id,
                                     'product_id' => $product['product_id'],
                                     'type' => $product['type'] ?? null,
                                     'mark' => $product['mark'] ?? null,
                                     'qty' => $product['qty'],
                                     'rate' => $product['rate'],
                                     'total' => $product['total'],
                                     'store_id' => 1, 
                                 ]);
                             }
                     
                             return redirect()->route('purchase-order.index')->with('success', 'Purchase Order Created Successfully');
                         } catch (\Exception $e) {
                             return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                         }
                     }
    
    
    
        public function edit($id)
        {
            $purchaseOrder = PurchaseOrder::with('details','products')->findOrFail($id);
            $suppliers = Supplier::all();
            $products = Product::all();
            return view('purchase-order.edit', compact('purchaseOrder', 'suppliers', 'products'));
        }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_no' => 'required',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'grand_total' => 'required|numeric|min:0',
        ]);
    
        try {
            // Update the sales order
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $purchaseOrder->update([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'grand_total' => $request->grand_total,
                'advance_amount' => $request->advance_amount ?? 0,
                'balance_amount' => $request->balance_amount ?? 0,
            ]);
    
            // Clear old sales order details and re-insert updated ones
            PurchaseOrderDetail::where('purchase_order_id', $id)->delete();
    
            foreach ($request->products as $product) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product['product_id'],
                    'type' => $product['type'] ,
                    'mark' => $product['mark'] ,
                    'qty' => $product['qty'],
                    'rate' => $product['rate'],
                    'total' => $product['total'],
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



    
}

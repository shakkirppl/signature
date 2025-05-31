<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesPayment;
use Illuminate\Support\Facades\DB;
use App\Models\SalesPaymentDetail;
use App\Models\Customer;
use App\Models\Outstanding;
use App\Models\PurchaseOrder;


class SalesPaymentController extends Controller
{
    public function create()
    {
        $customerOutstandingBalances = Outstanding::where('account_type', 'customer')
        ->select('account_id', DB::raw('SUM(payment) as total_payment, SUM(receipt) as total_receipt'))
        ->groupBy('account_id')
        ->get()
        ->mapWithKeys(function($item) {
            $balance = $item->total_payment - $item->total_receipt;
            return [$item->account_id => number_format($balance, 2, '.', '')];
        });
        $customers =Customer::all(); 
        $products = Product::all();
        $SalesOrders = SalesOrder::all();
        

       
        return view('sales-payment.create',['invoice_no'=>$this->invoice_no()],compact('customers','products','SalesOrders','customerOutstandingBalances'));
    }
    public function getShipmentBySalesNo(Request $request)
    {
        $salesNo = $request->sales_no;
    
        // Find the Purchase Order that matches the Sales Order ID
        $purchaseOrder = PurchaseOrder::where('SalesOrder_id', $salesNo)->first();
    
        return response()->json([
            'shipment_no' => $purchaseOrder && $purchaseOrder->shipment ? $purchaseOrder->shipment->shipment_no : null,
            'shipment_id' => $purchaseOrder ? $purchaseOrder->shipment_id : null,
        ]);
    }
    


    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('sales-payment',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }


 public function index()
{
    $SalesPayments = SalesPayment::with('customer')->get();
    return view('sales-payment.index', compact('SalesPayments'));
}


public function store(Request $request)
{
    // return $request->all();
    $request->validate([
        'order_no' => 'required',
        'date' => 'required|date',
        'sales_no' => 'required|exists:sales_order,id',
        'customer_id' => 'required|exists:customer,id',
        'grand_total' => 'required|numeric',
        'shipping_mode' => 'nullable|string',
        'shipping_agent' => 'nullable|string',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.qty' => 'required|numeric',
        'products.*.rate' => 'required|numeric',
        'shrinkage' =>'nullable|numeric',
        'shipment_id' => 'required',
    ]);

    DB::beginTransaction(); 

    try {
        $SalesPayment = SalesPayment::create([
            'order_no' => $request->order_no,
            'date' => $request->date,
            'sales_no' => $request->sales_no,
            'customer_id' => $request->customer_id,
            'shipping_agent' => $request->shipping_agent,
            'shipping_mode' => $request->shipping_mode,
            'shrinkage' => $request->shrinkage,
            'grand_total' => $request->grand_total,
            'advance_amount' => $request->advance_amount ?? 0,
            'balance_amount' => ($request->grand_total - ($request->advance_amount ?? 0)),
            'store_id' => 1, 
            'user_id' => auth()->id(), 
            'status' => 1,
            'shipment_id' => $request->shipment_id,
            'packaging' => $request->packaging,

            
        ]);

        foreach ($request->products as $product) {
            SalesPaymentDetail::create([
                'sales_payment_id' => $SalesPayment->id, 
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'rate' => $product['rate'],
                'total' => $product['qty'] * $product['rate'],
                'store_id' => 1, 
            ]);
        }
        InvoiceNumber::updateinvoiceNumber('sales-payment',1);

        DB::commit();

        return redirect()->route('sales_payment.index')->with('success', 'Sales Payment recorded successfully.');
    } catch (\Exception $e) {
        DB::rollBack(); 
        return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
    }
}




public function edit($id)
{
    $SalesPayment = SalesPayment::with('details')->findOrFail($id);
    $customers = Customer::all();
    $SalesOrders = SalesOrder::all();
    $products = Product::all();

    return view('sales-payment.edit', compact('SalesPayment', 'customers', 'SalesOrders', 'products'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'order_no' => 'required',
        'date' => 'required|date',
        'customer_id' => 'required|exists:customer,id', 
        'sales_no' => 'required|exists:sales_order,id',
        'shipping_mode' => 'nullable|string',
        'shipping_agent' => 'nullable|string',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.qty' => 'required|numeric',
        'products.*.rate' => 'required|numeric',
        'grand_total' => 'required|numeric',
        'shrinkage' =>'nullable|numeric',
       
    ]);

    DB::beginTransaction();

    try {
        $SalesPayment = SalesPayment::findOrFail($id);

        $SalesPayment->update([
            'order_no' => $request->order_no,
            'date' => $request->date,
            'sales_no' => $request->sales_no, 
            'customer_id' => $request->customer_id,
            'shipping_mode' => $request->shipping_mode ?? null, // Ensure null values are handled
            'shrinkage' => $request->shrinkage ?? null,
            'shipping_agent' => $request->shipping_agent ?? null,
            'grand_total' => $request->grand_total,
            'advance_amount' => $request->advance_amount ?? 0,
            'balance_amount' => ($request->grand_total - ($request->advance_amount ?? 0)),
            'store_id' => 1,
            'user_id' => auth()->id(),
            'status' => 1,
            'packaging' => $request->packaging,
        ]);

        $SalesPayment->details()->delete();

        foreach ($request->products as $product) {
            SalesPaymentDetail::create([
                'sales_payment_id' => $SalesPayment->id, 
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'rate' => $product['rate'],
                'total' => $product['qty'] * $product['rate'],
                'store_id' => 1,
            ]);
        }

        DB::commit();

        return redirect()->route('sales_payment.index')->with('success', 'Sales Payment updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
    }
}

public function view($id)
{
    $SalesPayment = SalesPayment::with(['customer',  'details.product'])->findOrFail($id);
    return view('sales-payment.view', compact('SalesPayment'));
}

public function destroy($id) {
    try {
        $SalesPayment = SalesPayment::findOrFail($id);

       
        SalesPaymentDetail::where('sales_payment_id', $id)->delete();

        
        $SalesPayment->delete();
        InvoiceNumber::decreaseInvoice('sales-payment', 1);

        return redirect()->route('sales_payment.index')->with('success', 'Record deleted successfully');
    } catch (\Exception $e) {
        return redirect()->route('sales_payment.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}



public function report(Request $request)
{
    $query = SalesPayment::with('customer');

    if ($request->customer_id) {
        $query->where('customer_id', $request->customer_id);
    }

    if ($request->from_date && $request->to_date) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    $SalesPayments = $query->get();
    $customers = Customer::all();

    return view('sales-payment.report', compact('SalesPayments', 'customers'));
}

public function printInvoice($order_no)
{
    $order = SalesPayment::where('order_no', $order_no)
        ->join('customer', 'sales_payment_master.customer_id', '=', 'customer.id')
        ->select('sales_payment_master.*', 'customer.customer_code') // Fetch customer_code
        ->firstOrFail();

    $products = SalesPaymentDetail::where('sales_payment_id', $order->id)
        ->join('product', 'sales_payment_detail.product_id', '=', 'product.id')
        ->select(
 'product.product_name as product_name',            'product.description',
            'product.hsn_code',
            'sales_payment_detail.qty as quantity',
            'sales_payment_detail.rate as price',
           
        )
        ->get();

        $totalPackaging = $order->sum('packaging');
        $totalPrice = $products->sum('price');
        $totalWeight = $order->details->sum('quantity');
       
        $totalAmount = $products->sum(fn($product) => $product->quantity * $product->price);
   
    

    $total_kg = $products->sum('quantity');

    return view('sales-payment.invoice-print', compact('order', 'products', 'totalPackaging', 'total_kg','totalWeight','totalAmount','totalPrice' ));
}

public function getOutstandingBalance($customerId)
{
    try {
        $outstanding = Outstanding::where('account_id', $customerId)
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


public function requestDelete($id)
{
    $user = auth()->user();

    if ($user->designation_id != 3) {
        abort(403);
    }

    $salesPayment = SalesPayment::findOrFail($id);
    $salesPayment->delete_status = 1; 
    $salesPayment->save();

    return redirect()->route('sales_payment.index')->with('success', 'Delete request submitted for admin approval.');
}

public function pendingDelete()
{
    if (auth()->user()->designation_id != 1) {
        abort(403);
    }

    $SalesPayments = SalesPayment::where('delete_status', 1)->with(['customer', 'SalesOrder', 'shipment'])->get();
    return view('sales-payment.pending-delete', compact('SalesPayments'));
}


}

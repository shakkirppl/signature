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
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('sales_order',Auth::user()->store_id=1);
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
                     ]);
                 
                     try {
                        InvoiceNumber::updateinvoiceNumber('sales_order',1);

                         // Fetch the customer details
                         $customer = Customer::find($request->customer_id);
                 
                         // Calculate the credit limit expiration date
                         if ($customer->credit_limit_days > 0) {
                             $credit_limit_date = Carbon::parse($customer->created_at)->addDays($customer->credit_limit_days);
                 
                             // Check if the sales order date exceeds the credit limit date
                             if (Carbon::parse($request->date)->greaterThan($credit_limit_date)) {
                                 return redirect()->back()->withErrors(['error' => 'Credit limit days have expired for this customer.']);
                             }
                         }
                 
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





}

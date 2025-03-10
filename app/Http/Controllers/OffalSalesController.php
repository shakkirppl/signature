<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\OffalSales;
use App\Models\Localcustomer;
use App\Models\OffalSalesDetail;
use Illuminate\Support\Facades\DB;

class OffalSalesController extends Controller
{
    public function create()
    {
        $localcustomers = Localcustomer::all(); 
        $products = Product::all();
        $shipments = Shipment::all();
       
        return view('offal-sales.create',['invoice_no'=>$this->invoice_no()],compact('localcustomers','products','shipments'));
    }

    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('offal-sales',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }


                 public function store(Request $request)
                 {
                     $request->validate([
                         'order_no' => 'required',
                         'date' => 'required|date',
                         'shipment_id' => 'required|exists:shipment,id',
                         'lo_customer_id' => 'required|exists:local_customer,id',
                         'grand_total' => 'required|numeric|min:0',
                         'products' => 'required|array',
                         'products.*.product_id' => 'required|exists:product,id',
                         'products.*.qty' => 'required|numeric|min:0.01',
                         'products.*.rate' => 'required|numeric|min:0.01',
                     ]);
                 
                     DB::beginTransaction(); 
                 
                     try {
                         
                         $offalSale = OffalSales::create([
                             'order_no' => $request->order_no,
                             'date' => $request->date,
                             'shipment_id' => $request->shipment_id,
                             'lo_customer_id' => $request->lo_customer_id,
                             'grand_total' => $request->grand_total,
                             'advance_amount' => $request->advance_amount ?? 0,
                             'balance_amount' => ($request->grand_total - ($request->advance_amount ?? 0)),
                             'store_id' => 1, 
                             'user_id' => auth()->id(), 
                             'status' => 1,
                         ]);
                 
                         
                         foreach ($request->products as $product) {
                            OffalSalesDetail::create([
                                 'offal_sales_id' => $offalSale->id, 
                                 'product_id' => $product['product_id'],
                                 'qty' => $product['qty'],
                                 'rate' => $product['rate'],
                                 'total' => $product['qty'] * $product['rate'],
                                 'store_id' => 1, 
                             ]);
                         }
                         InvoiceNumber::updateinvoiceNumber('offal-sales',1);

                         DB::commit();
                 
                         return redirect()->route('offal-sales.index')->with('success', 'Offal Sale recorded successfully.');
                     } catch (\Exception $e) {
                         DB::rollBack(); 
                         return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
                     }
}


public function index()
{
    $offalSales = OffalSales::with('localcustomer')->get();
    return view('offal-sales.index', compact('offalSales'));
}


public function edit($id)
{
    $offalSale = OffalSales::with('details')->findOrFail($id);
    $localcustomers = Localcustomer::all();
    $shipments = Shipment::where('shipment_status', 0)->get();
    $products = Product::all();

    return view('offal-sales.edit', compact('offalSale', 'localcustomers', 'shipments', 'products'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'order_no' => 'required',
        'date' => 'required|date',
        'shipment_id' => 'required|exists:shipment,id',
        'lo_customer_id' => 'required|exists:local_customer,id',
        'grand_total' => 'required|numeric|min:0',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.qty' => 'required|numeric|min:0.01',
        'products.*.rate' => 'required|numeric|min:0.01',
    ]);

    DB::beginTransaction();

    try {
        
        $offalSale = OffalSales::findOrFail($id);

        
        $offalSale->update([
            'order_no' => $request->order_no,
            'date' => $request->date,
            'shipment_id' => $request->shipment_id,
            'lo_customer_id' => $request->lo_customer_id,
            'grand_total' => $request->grand_total,
            'advance_amount' => $request->advance_amount ?? 0,
            'balance_amount' => ($request->grand_total - ($request->advance_amount ?? 0)),
            'store_id' => 1,
            'user_id' => auth()->id(),
            'status' => 1,
        ]);

        
        $offalSale->details()->delete();

       
        foreach ($request->products as $product) {
            OffalSalesDetail::create([
                'offal_sales_id' => $offalSale->id,
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'rate' => $product['rate'],
                'total' => $product['qty'] * $product['rate'],
                'store_id' => 1,
            ]);
        }

        DB::commit();

        return redirect()->route('offal-sales.index')->with('success', 'Offal Sale updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
    }
}

public function view($id)
{
    $offalSale = OffalSales::with(['localcustomer', 'shipment', 'details.product'])->findOrFail($id);
    return view('offal-sales.view', compact('offalSale'));
}

public function destroy($id)
{
    try {
        $offalSale = OffalSales::findOrFail($id);
        $offalSale->delete();
        return redirect()->route('offal-sales.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('offal-sales.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}


public function report(Request $request)
{
    $query = OffalSales::with('localcustomer');

    if ($request->customer_id) {
        $query->where('lo_customer_id', $request->customer_id);
    }

    if ($request->from_date && $request->to_date) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    $offalSales = $query->get();
    $customers = Localcustomer::all();

    return view('offal-sales.report', compact('offalSales', 'customers'));
}


}
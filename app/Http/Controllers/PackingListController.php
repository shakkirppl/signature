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

public function show($id)
{
    $packing = PackingListMaster::with('details')->findOrFail($id);
    return view('packing_list.show', compact('packing'));
}


public function destroy($id)
{
    $packing = PackingListMaster::findOrFail($id);
    $packing->delete();
    
    return redirect()->route('packinglist.index')->with('success', 'Packing list deleted successfully.');
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


 
}

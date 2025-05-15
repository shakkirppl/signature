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
use App\Models\RequestingForm;
use App\Models\BankMaster;
use App\Models\RequestingFormDetail;
// 
class RequestingFormController extends Controller
{
    public function index()
{
$requestingForms = RequestingForm::with(['supplier', 'shipment', 'salesOrder', 'user'])
    ->where('status', 'pending')
    ->latest()->get();    return view('requesting-form.index', compact('requestingForms'));
}

    public function create()
    {
        
        $suppliers = Supplier::all(); 
         $banks = BankMaster::all(); 
        $products = Product::all();
        $SalesOrders=SalesOrder::all();
        $shipments = Shipment::where('shipment_status', 0)->get();
        $purchase_order_no = InvoiceNumber::ReturnInvoice('purchase_order', 1);
        $advance_invoice_no = InvoiceNumber::ReturnInvoice('advance_request', 1);
        return view('requesting-form.create',['invoice_no'=>$this->invoice_no()],compact('suppliers','products','shipments','SalesOrders','purchase_order_no','advance_invoice_no','banks'));
    }

    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('purchase_order',1);
         $advanceRequestInvoice = InvoiceNumber::ReturnInvoice('advance_request', 1);

                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

                public function store(Request $request)
{
    $request->validate([
        'order_no' => 'required|unique:purchase_order,order_no',
        'date' => 'required|date',
        'supplier_id' => 'nullable|exists:supplier,id',
        'shipment_id'=> 'required|exists:shipment,id',
        'SalesOrder_id' => 'required|exists:sales_order,id',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.qty' => 'required|numeric|min:1',
    ]);

    DB::beginTransaction();

    try {
        // Create the requesting form
        $requestingForm = RequestingForm::create([
            'invoice_no' => $request->invoice_no,
            'order_no' => $request->order_no,
            'supplier_no' => $request->supplier_no,
            'date' => $request->date,
            'supplier_id' => $request->supplier_id,
            'shipment_id' => $request->shipment_id,
            'SalesOrder_id' => $request->SalesOrder_id,
            'ssf_no' => null,
            'market' => $request->market,
            'advance_amount' => isset($request->advance_amount) ? (float) str_replace(',', '', $request->advance_amount) : 0,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
            'payment_type' => $request->payment_type,
            'phone_number' => $request->phone_number,
            'user_id' => auth()->id(),
           'status' => 'pending',
        ]);

      
        // Store details in the request_form_details table
        foreach ($request->products as $product) {
            RequestingFormDetail::create([
                'requesting_form_id' => $requestingForm->id,
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'male' => $product['male'] ?? 0,  // use 0 if not provided
                'female' => $product['female'] ?? 0,
            ]);
        }

        // Update invoice numbers if needed
        InvoiceNumber::updateinvoiceNumber('purchase_order', 1);
        InvoiceNumber::updateinvoiceNumber('advance_request', 1);

        DB::commit();
        return redirect()->route('requesting-form.index')->with('success', 'Advance Request Created Successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function accept($id)
{
    $form = RequestingForm::with('products')->findOrFail($id);

    DB::beginTransaction();

    try {
        $purchaseOrder = PurchaseOrder::create([
            'order_no' => $form->order_no,
            'date' => $form->date,
            'supplier_id' => $form->supplier_id,
            'grand_total' => 0,
            'advance_amount' => $form->advance_amount,
            'balance_amount' => 0,
            'store_id' => 1,
            'user_id' => $form->user_id,
            'status' => 1,
            'inspection_status' => 0,
            'shipment_id' => $form->shipment_id,
            'SalesOrder_id' => $form->SalesOrder_id,
            'requesting_form_id' => $form->id,
        ]);

        foreach ($form->products as $product) {
            PurchaseOrderDetail::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $product->product_id,
                'type' => null,
                'qty' => $product->qty,
                'male' => $product->male,
                'female' => $product->female,
                'rate' => 0,
                'total' => 0,
                'store_id' => 1,
            ]);
        }

        $form->update(['status' => 'accepted']);
        DB::commit();

        return redirect()->route('requesting-form.index')->with('success', 'Request accepted and Purchase Order created.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function reject($id)
{
    $form = RequestingForm::findOrFail($id);
    $form->update(['status' => 'rejected']);

    return redirect()->route('requesting-form.index')->with('success', 'Request rejected successfully.');
}

public function show($id)
{
    $form = RequestingForm::with(['supplier', 'shipment', 'salesOrder', 'user','bank'])->findOrFail($id);
    return view('requesting-form.show', compact('form'));
}


public function destroy($id)
{
    $requestingForm = RequestingForm::findOrFail($id);

    // Delete associated PurchaseOrder and its details
    if ($requestingForm->purchaseOrder) {
        $purchaseOrder = $requestingForm->purchaseOrder;

        // Delete all details
        $purchaseOrder->details()->delete();

        // Delete purchase order
        $purchaseOrder->delete();
    }

    // Delete requesting form
    $requestingForm->delete();
    InvoiceNumber::decreaseInvoice('advance_request', 1);

    return redirect()->route('requesting-form.index')->with('success', 'Request and related orders deleted successfully.');
}

public function edit($id)
{
    $requestingForm = RequestingForm::with('details')->findOrFail($id);

    $suppliers = Supplier::all(); 
    $banks = BankMaster::all(); 
    $products = Product::all();
    $SalesOrders = SalesOrder::all();
    $shipments = Shipment::where('shipment_status', 0)->get();

    return view('requesting-form.edit', compact(
        'requestingForm', 'suppliers', 'banks', 'products', 'SalesOrders', 'shipments'
    ));
}

public function update(Request $request, $id)
{
    $request->validate([
        'order_no' => 'required|unique:purchase_order,order_no,' . $id,
        'date' => 'required|date',
        'supplier_id' => 'nullable|exists:supplier,id',
        'shipment_id'=> 'required|exists:shipment,id',
        'SalesOrder_id' => 'required|exists:sales_order,id',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.qty' => 'required|numeric|min:1',
    ]);

    DB::beginTransaction();

    try {
        $requestingForm = RequestingForm::findOrFail($id);
        $requestingForm->update([
            'order_no' => $request->order_no,
            'invoice_no' => $request->invoice_no,
            'date' => $request->date,
            'supplier_id' => $request->supplier_id,
            'supplier_no' => $request->supplier_no,
            'shipment_id' => $request->shipment_id,
            'SalesOrder_id' => $request->SalesOrder_id,
            'market' => $request->market,
            'advance_amount' => isset($request->advance_amount) ? (float) str_replace(',', '', $request->advance_amount) : 0,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
            'payment_type' => $request->payment_type,
            'phone_number' => $request->phone_number,
        ]);

        // Delete existing details
        RequestingFormDetail::where('requesting_form_id', $requestingForm->id)->delete();

        // Reinsert details
        foreach ($request->products as $product) {
            RequestingFormDetail::create([
                'requesting_form_id' => $requestingForm->id,
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'male' => $product['male'] ?? 0,
                'female' => $product['female'] ?? 0,
            ]);
        }

        DB::commit();
        return redirect()->route('requesting-form.index')->with('success', 'Request Updated Successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}


public function updateAdvance(Request $request, $id)
{
    $request->validate([
        'advance_amount' => 'required|numeric',
    ]);

    $form = RequestingForm::findOrFail($id);
    $form->advance_amount = $request->advance_amount;
    $form->save();

    return redirect()->route('requesting-form.index')->with('success', 'Advance amount updated successfully.');
}




}
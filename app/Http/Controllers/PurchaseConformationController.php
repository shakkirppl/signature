<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightCalculatorMaster;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseConformation;
use App\Models\PurchaseConformationDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseExpense;
use App\Models\AccountHead;
use App\Models\WeightCalculatorDetail;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\SupplierAdvance;


class PurchaseConformationController extends Controller
{
    public function index()
{
   
    $conformations = WeightCalculatorMaster::with('supplier', 'user')->where('status', 3)->get();
   

   
    return view('purchase-conformation.index', compact('conformations'));
}

public function Confirm($id)
{
    $WeightCalculatorMaster = WeightCalculatorMaster::with(['details.product', 'supplier', 'shipment'])->findOrFail($id);
    
    // Fetch purchase order
    $order = PurchaseOrder::find($WeightCalculatorMaster->purchaseOrder_id);
    
    // Calculate total advance amount
    $purchaseAdvance = $order->advance_amount ?? 0;

    $supplierAdvanceTotal = SupplierAdvance::where('supplier_id', $order->supplier_id ?? 0)
        ->where('shipment_id', $order->shipment_id ?? 0)
        ->where('purchaseOrder_id', $order->id ?? 0)
        ->sum('advance_amount');

    // Ensure totalAdvanceAmount is never null
    $totalAdvanceAmount = ($purchaseAdvance + $supplierAdvanceTotal) ?? 0;

    // Fetch related COA (Chart of Accounts)
    $coa = AccountHead::whereIn('parent_id', function ($query) {
        $query->select('id')
              ->from('account_heads')
              ->whereIn('name', ['Expenses']);
    })->get();

    return view('purchase-conformation.confirm', [
        'invoice_no' => $this->invoice_no(),
        'WeightCalculatorMaster' => $WeightCalculatorMaster,
        'details' => $WeightCalculatorMaster->details,
        'products' => Product::all(),
        'shipment' => Shipment::where('shipment_status', 0)->get(),
        'coa' => $coa,
        'order' => $order,
        'totalAdvanceAmount' => $totalAdvanceAmount 
    ]);
}




public function invoice_no(){
    try {
         
     return $invoice_no =  InvoiceNumber::ReturnInvoice('purchase_conformation',1);
              } catch (\Exception $e) {
     
        return $e->getMessage();
      }
             }

             // return $request->all();

             public function store(Request $request)
             {
                
                //   dd($request);
                // return $request->all();
                 $validatedData = $request->validate([
                     'weight_id' => 'required|exists:weight_calculator_master,id',
                     'weight_code' => 'required|string',
                     'purchaseOrder_id'=>'required',
                     'inspection_id'=>'required',
                     'invoice_number' => 'required|string',
                     'shipment_id' => 'required|exists:shipment,id',
                     'date' => 'required|date',
                     'supplier_id' => 'required|exists:supplier,id',
                     'item_total' => 'required',
                     'total_expense' => 'required',
                     'grand_total' => 'required',
                     'advance_amount' => 'required',
                     'balance_amount' => 'required',
                     'products' => 'required|array',
                     'products.*.product_id' => 'required|exists:product,id',
                     'products.*.type' => 'nullable|string',
                   
                     'products.*.total_accepted_qty' => 'required|string',
                     'products.*.total_weight' => 'required|string',
                     'products.*.transportation_amount' => 'required|min:1',
                     'products.*.rate' => 'required',
                     'products.*.total' => 'required',
                    //  'expenses' => 'nullable|array',
                     //  'expenses.*.expense_id' => 'nullable|exists:account_heads,id',
                    //  'expenses.*.amount' => 'nullable|numeric',
                 ]);
             
                 DB::beginTransaction();
                 try {
                     
                     $purchaseConformation = PurchaseConformation::create([
                         'weight_id' => $validatedData['weight_id'],
                         'inspection_id' => $validatedData['inspection_id'],
                         'purchaseOrder_id'=> $validatedData['purchaseOrder_id'],
                         'weight_code' => $validatedData['weight_code'],
                         'invoice_number' => $validatedData['invoice_number'],
                         'shipment_id' => $validatedData['shipment_id'],
                         'date' => $validatedData['date'],
                         'supplier_id' => $validatedData['supplier_id'],
                         'item_total' => isset($validatedData['item_total']) ? (float) str_replace(',', '', $validatedData['item_total']) : 0,
                         'total_expense' => isset($validatedData['total_expense']) ? (float) str_replace(',', '', $validatedData['total_expense']) : 0,
                         'grand_total' => isset($validatedData['grand_total']) ? (float) str_replace(',', '', $validatedData['grand_total']) : 0,
                         'advance_amount' => isset($validatedData['advance_amount']) ? (float) str_replace(',', '', $validatedData['advance_amount']) : 0,
                         'balance_amount' => isset($validatedData['balance_amount']) ? (float) str_replace(',', '', $validatedData['balance_amount']) : 0,
                         'status' => 1,
                         'shipment_status' => 0,
                         'user_id' => auth()->id(),
                         'store_id' => 1,
                     ]);
             
                     // Store purchase confirmation details
                     foreach ($validatedData['products'] as $product) {
                         PurchaseConformationDetail::create([
                             'conformation_id' => $purchaseConformation->id,
                             'product_id' => $product['product_id'],
                             'type' => null,
                             'mark' => null,
                             'total_accepted_qty' => $product['total_accepted_qty'],
                             'total_weight' => $product['total_weight'],
                             'transportation_amount' => isset($product['transportation_amount']) ? (float) str_replace(',', '', $product['transportation_amount']) : 0,
                             'rate' =>isset($product['rate']) ? (float) str_replace(',', '', $product['rate']) : 0,
                             'total' => isset($product['total']) ? (float) str_replace(',', '', $product['total']) : 0,
                             'store_id' => 1,
                         ]);
                     }
                  

                     $WeightCalculatorMaster = WeightCalculatorMaster::find($request->weight_id);
                     if ($WeightCalculatorMaster) {
                         $WeightCalculatorMaster->update(['status' => 0]);
                     }
                     InvoiceNumber::updateinvoiceNumber('purchase_conformation',1);

                     DB::commit();
             
                     return redirect()->route('purchase-conformation.index')->with('success', 'Purchase Confirmation saved successfully!');
                 } catch (\Exception $e) {
                     DB::rollBack();
                     \Log::error('PurchaseConformation store error: ' . $e->getMessage());
             
                     return redirect()->back()->with('error', 'Error saving data: ' . $e->getMessage());
                 }
             }
             
             
             


public function report(Request $request)
{
    $query = PurchaseConformation::with('supplier');

    if ($request->supplier_id) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->from_date && $request->to_date) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    $conformations = $query->get();
    $suppliers = Supplier::all();

    return view('purchase-conformation.report', compact('conformations', 'suppliers'));
}



public function view($id)
{
    $conformation = PurchaseConformation::with('supplier', 'details.product')->findOrFail($id);
    return view('purchase-conformation.view', compact('conformation'));
}


public function edit($id)
{
    // Fetch the purchase confirmation record
    $purchaseConfirmation = PurchaseConformation::with('details.product', 'supplier', 'shipment')->findOrFail($id);

    // Fetch all available products
    $products = Product::all();

    return view('purchase-conformation.edit', compact('purchaseConfirmation', 'products'));
}

public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'weight_id' => 'required|exists:weight_calculator_master,id',
        'weight_code' => 'required|string',
        'purchaseOrder_id' => 'required',
        'inspection_id' => 'required',
        'invoice_number' => 'required|string',
        'shipment_id' => 'required|exists:shipment,id',
        'date' => 'required|date',
        'supplier_id' => 'required|exists:supplier,id',
        'item_total' => 'required',
        'total_expense' => 'required',
        'grand_total' => 'required',
        'advance_amount' => 'required',
        'balance_amount' => 'required',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:product,id',
        'products.*.type' => 'nullable|string',
        'products.*.total_accepted_qty' => 'required|string',
        'products.*.total_weight' => 'required|string',
        'products.*.transportation_amount' => 'required',
        'products.*.rate' => 'required',
        'products.*.total' => 'required',
    ]);

    DB::beginTransaction();
    try {
        $purchaseConformation = PurchaseConformation::findOrFail($id);

        $purchaseConformation->update([
            'weight_id' => $validatedData['weight_id'],
            'inspection_id' => $validatedData['inspection_id'],
            'purchaseOrder_id' => $validatedData['purchaseOrder_id'],
            'weight_code' => $validatedData['weight_code'],
            'invoice_number' => $validatedData['invoice_number'],
            'shipment_id' => $validatedData['shipment_id'],
            'date' => $validatedData['date'],
            'supplier_id' => $validatedData['supplier_id'],
            'item_total' => (float) str_replace(',', '', $validatedData['item_total']),
            'total_expense' => (float) str_replace(',', '', $validatedData['total_expense']),
            'grand_total' => (float) str_replace(',', '', $validatedData['grand_total']),
            'advance_amount' => (float) str_replace(',', '', $validatedData['advance_amount']),
            'balance_amount' => (float) str_replace(',', '', $validatedData['balance_amount']),
            'status' => 1,
            'shipment_status' => 0,
            'user_id' => auth()->id(),
        ]);

        // Update purchase confirmation details
        $purchaseConformation->details()->delete();
        foreach ($validatedData['products'] as $product) {
            PurchaseConformationDetail::create([
                'conformation_id' => $purchaseConformation->id,
                'product_id' => $product['product_id'],
                'type' => null,
                'mark' => null,
                'total_accepted_qty' => $product['total_accepted_qty'],
                'total_weight' => $product['total_weight'],
                'transportation_amount' => (float) str_replace(',', '', $product['transportation_amount']),
                'rate' => (float) str_replace(',', '', $product['rate']),
                'total' => (float) str_replace(',', '', $product['total']),
                'store_id' => 1,
            ]);
        }

     
        DB::commit();
        return redirect()->route('purchase-confirmation.report')->with('success', 'Purchase Confirmation updated successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('PurchaseConformation update error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error updating data: ' . $e->getMessage());
    }
}


}

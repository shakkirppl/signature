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
   
    $conformations = WeightCalculatorMaster::with('supplier', 'user')->where('status', 1)->get();
   

   
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
                
                //   return $request->all();
                 $validatedData = $request->validate([
                     'weight_id' => 'required|exists:weight_calculator_master,id',
                     'weight_code' => 'required|string',
                     'purchaseOrder_id'=>'required',
                     'inspection_id'=>'required',
                     'invoice_number' => 'required|string',
                     'shipment_id' => 'required|exists:shipment,id',
                     'date' => 'required|date',
                     'supplier_id' => 'required|exists:supplier,id',
                     'item_total' => 'required|numeric',
                     'total_expense' => 'required|numeric',
                     'grand_total' => 'required|numeric',
                     'advance_amount' => 'required|numeric',
                     'balance_amount' => 'required|numeric',
                     'products' => 'required|array',
                     'products.*.product_id' => 'required|exists:product,id',
                     'products.*.type' => 'nullable|string',
                   
                     'products.*.total_accepted_qty' => 'required|string',
                     'products.*.total_weight' => 'required|string',
                     'products.*.transportation_amount' => 'required|numeric|min:1',
                     'products.*.rate' => 'required|numeric',
                     'products.*.total' => 'required|numeric',
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
                         'item_total' => $validatedData['item_total']?? 0,
                         'total_expense' => $validatedData['total_expense']?? 0,
                         'grand_total' => $validatedData['grand_total']?? 0,
                         'advance_amount' => $validatedData['advance_amount']?? 0,
                         'balance_amount' => $validatedData['balance_amount'] ?? 0,
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
                             'transportation_amount' => $product['transportation_amount'],
                             'rate' => $product['rate'],
                             'total' => $product['total'],
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


}

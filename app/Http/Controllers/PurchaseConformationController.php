<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\Product;
use App\Models\PurchaseConformation;
use App\Models\PurchaseConformationDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseExpense;


use Carbon\Carbon;

use App\Models\Supplier;

class PurchaseConformationController extends Controller
{
    public function index()
{
   
    $conformations = Inspection::with('supplier', 'user')->where('purchase_status', 0)->get();
   

   
    return view('purchase-conformation.index', compact('conformations'));
}


public function Confirm($id)
{
    $inspection = Inspection::with(['details.product', 'supplier', 'purchase_order','shipment'])->findOrFail($id);
    $coa = \App\Models\AccountHead::whereIn('parent_id', function ($query) {
        $query->select('id')
              ->from('account_heads')
              ->whereIn('name', ['Expenses']);
    })->get();

    return view('purchase-conformation.confirm',['invoice_no' => $this->invoice_no()], [
        'inspection' => $inspection,
        'suppliers' => Supplier::all(),
        'products' => Product::all(),
        'shipment' => Shipment::where('shipment_status', 0)->get(),
        'coa' => $coa,
    ]);
}



public function invoice_no(){
    try {
         
     return $invoice_no =  InvoiceNumber::ReturnInvoice('purchase_conformation',Auth::user()->store_id=1);
              } catch (\Exception $e) {
     
        return $e->getMessage();
      }
             }

             // return $request->all();

             public function store(Request $request)
             {
                
                // return $request->all();
                 $validatedData = $request->validate([
                     'inspection_id' => 'required|exists:inspection,id',
                     'order_no' => 'required|string',
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
                     'products.*.type' => 'required|string',
                     'products.*.mark' => 'required|string',
                     'products.*.accepted_qty' => 'required|numeric|min:1',
                     'products.*.rate' => 'required|numeric',
                     'products.*.total' => 'required|numeric',
                     'expenses' => 'nullable|array',
                     'expenses.*.expense_id' => 'nullable|exists:account_heads,id',
                     'expenses.*.amount' => 'nullable|numeric',
                 ]);
             
                 DB::beginTransaction();
                 try {
                     
                     $purchaseConformation = PurchaseConformation::create([
                         'inspection_id' => $validatedData['inspection_id'],
                         'order_no' => $validatedData['order_no'],
                         'invoice_number' => $validatedData['invoice_number'],
                         'shipment_id' => $validatedData['shipment_id'],
                         'date' => $validatedData['date'],
                         'supplier_id' => $validatedData['supplier_id'],
                         'item_total' => $validatedData['item_total'],
                         'total_expense' => $validatedData['total_expense'],
                         'grand_total' => $validatedData['grand_total'],
                         'advance_amount' => $validatedData['advance_amount'],
                         'balance_amount' => $validatedData['balance_amount'],
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
                             'type' => $product['type'],
                             'mark' => $product['mark'],
                             'accepted_qty' => $product['accepted_qty'],
                             'rate' => $product['rate'],
                             'total' => $product['total'],
                             'store_id' => 1,
                         ]);
                     }
                     if (!empty($request->expense_id)) {
                        foreach ($request->expense_id as $index => $expense_id) {
                            if (!empty($expense_id) && !empty($request->amount[$index])) {
                                PurchaseExpense::create([
                                    'purchase_id' => $purchaseConformation->id,
                                    'expense_id' => $expense_id,
                                    'amount' => $request->amount[$index],
                                    'store_id' => 1
                                ]);
                            }
                        }
                    }

                    
             
                    
                     $inspection = Inspection::find($request->inspection_id);
                     if ($inspection) {
                         $inspection->update(['purchase_status' => 1]);
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

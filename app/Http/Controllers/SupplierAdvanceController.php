<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Shipment;
use App\Models\BankMaster;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierAdvance;
use App\Models\AccountTransactions;



class SupplierAdvanceController extends Controller
{
 
    public function index()
    {
        $supplierAdvances = SupplierAdvance::with(['shipment', 'supplier'])->get();
        return view('supplier-advance.index', compact('supplierAdvances'));
    }
    

    public function create()
{
    $banks = BankMaster::all();
    $shipments = Shipment::whereIn('id', PurchaseOrder::pluck('shipment_id'))->get();
    
    return view('supplier-advance.create', compact( 'shipments','banks'),['invoice_no'=>$this->invoice_no()]);
}


public function invoice_no(){
    try {
         
     return $invoice_no =  InvoiceNumber::ReturnInvoice('supplier_advance',1);
              } catch (\Exception $e) {
     
        return $e->getMessage();
      }
             }

             public function getSuppliersByShipment(Request $request)
             {
                 $shipmentId = $request->shipment_id;
             
                 // Fetch distinct suppliers from purchaseorder table
                 $suppliers = PurchaseOrder::where('shipment_id', $shipmentId)
                                 ->with('supplier') // Ensure supplier relationship exists
                                 ->select('supplier_id')
                                 ->distinct()
                                 ->get();
             
                 $supplierData = [];
                 foreach ($suppliers as $supplier) {
                     if ($supplier->supplier) { // Ensure supplier exists
                         $supplierData[] = [
                             'id' => $supplier->supplier_id,
                             'name' => $supplier->supplier->name
                         ];
                     }
                 }
             
                 return response()->json(['suppliers' => $supplierData]);
             }
             
             
             public function getOrdersBySupplier(Request $request)
             {
                 $supplierId = $request->supplier_id;
                 $shipmentId = $request->shipment_id;
             
                 // Fetch order details from purchaseorder table
                 $order = PurchaseOrder::where('supplier_id', $supplierId)
                             ->where('shipment_id', $shipmentId)
                             ->first();
             
                 return response()->json([
                     'order_no' => $order ? $order->order_no : '',
                     'purchaseOrder_id' => $order ? $order->id : ''
                 ]);
             }
             
             

public function store(Request $request)
{
    // return $request->all();
    $request->validate([
        'shipment_id' => 'required',
        'supplier_id' => 'required',
        'date' => 'required|date',
        'type' => 'required',
        'advance_amount' => 'required',
        'purchaseOrder_id'=>'required',
    ]);

    DB::beginTransaction();
    try {
        $user = Auth::user();
        $accountHeadId = $user->account_head_id; 
        $supplier = Supplier::find($request->supplier_id);


if (!$supplier) {
return redirect()->back()->withErrors(['error' => 'Supplier not found.'])->withInput();
}
        // Store supplier advance
        $supplierAdvance = SupplierAdvance::create([
            'code' => $this->invoice_no(),
            'date' => $request->date,
            'purchaseOrder_id' => $request->purchaseOrder_id,
            'shipment_id' => $request->shipment_id,
            'supplier_id' => $request->supplier_id,
            'type' => $request->type,
            'order_no' => $request->order_no,
            'advance_amount' => isset($request->advance_amount)  ? (float) str_replace(',', '', $request->advance_amount) : 0.00,

            'bank_id' => $request->type === 'bank' ? $request->bank_id : null,
            'store_id' =>1,
            'user_id' => Auth::id(),
            'description' => $request->description,
        ]);
        InvoiceNumber::updateinvoiceNumber('supplier_advance',1);

        $group_no = AccountTransactions::orderBy('id', 'desc')->max('group_no') + 1;

        AccountTransactions::storeTransaction(
            $group_no,
            $supplierAdvance->date,
            $accountHeadId, 
            $supplierAdvance->id,
            $supplier->account_head_id,  
            "supplier advance Invoice No: " . $supplierAdvance->code,
            "supplier advance",
            null,
            $supplierAdvance->advance_amount, 
        );

        AccountTransactions::storeTransaction(
            $group_no,
            $supplierAdvance->date,
            $supplier->account_head_id, 
            $supplierAdvance->id,
            $accountHeadId, 
            "supplier advance  Invoice No: " . $supplierAdvance->code,
            "supplier advance",
            $supplierAdvance->advance_amount, 
            null
        );
      
        

        DB::commit();
        return redirect()->route('supplieradvance.index')->with('success', 'Supplier Advance stored successfully.');
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('update  store error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    }
}

}

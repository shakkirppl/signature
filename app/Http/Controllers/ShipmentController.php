<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseConformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;
use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseConformationDetail;



class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::where('shipment_status', 0)->get();
        return view('shipment.index', compact('shipments'));
    }
    



   public function create()
{
   
   
    return view('shipment.create', ['invoice_no' => $this->invoice_no()], );
}


    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('shipment',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

                 public function store(Request $request)
                 {
                     $request->validate([
                         'shipment_no' => 'required|unique:shipment',
                         'date' => 'required|date',
                         'time' => 'required',
                     ]);
             
                     Shipment::create([
                         'shipment_no' => $request->shipment_no,
                         'date' => $request->date,
                         'time' => $request->time,
                        'shipment_status' => 0,
                        
                     ]);
                     InvoiceNumber::updateinvoiceNumber('shipment',1);

                     return redirect()->route('shipment.index')->with('success', 'Shipment created successfully.');
                 }


                 public function destroy($id)
                 {
                     $shipment = Shipment::findOrFail($id);
                     $shipment->update(['shipment_status' => 1]);
                     InvoiceNumber::decreaseInvoice('shipment', 1);
                 
                     return redirect()->route('shipment.index')->with('success', 'Shipment status updated successfully.');
                 }
                 
                 
   
    //              public function report()
    // {
    //     $shipments = Shipment::OrderBy('id','DESC')->get();
    //     return view('shipment.report', compact('shipments'));
    // }
    public function report()
{
    $shipments = Shipment::where('shipment_status', 0)
                         ->orderBy('id', 'DESC')
                         ->get();

    return view('shipment.report', compact('shipments'));
}

    public function view($id)
    {
        $shipments = Shipment::find($id);
        return view('shipment.view', compact('shipments'));
    }
    public function shipment_suppllier_final_payment_report($id)
    {
         $shipments = Shipment::find($id);
          $poSuppllier=PurchaseOrder::where('shipment_id',$id)->pluck('supplier_id');
        $supplier=Supplier::whereIn('id',$poSuppllier)->get();
        $purchaseConformationDetail=[];
        $PurchaseOrder=[];
        return view('shipment.supplier-report', compact('shipments','supplier','PurchaseOrder','purchaseConformationDetail'));
    }
    public function shipment_suppllier_final_payment_report_detail(Request $request)
    {
         $shipments = Shipment::find($request->shipment_id);
         $supplier_id=$request->supplier_id;
         $poSuppllier=PurchaseOrder::where('shipment_id',$request->shipment_id)->pluck('supplier_id');

       
        $supplier=Supplier::whereIn('id',$poSuppllier)->get();

         $PurchaseOrder = PurchaseOrder::where('shipment_id',$request->shipment_id)->where('supplier_id',$supplier_id)->where('advance_amount','>',0)->get();
         $purchaseConformationDetail = PurchaseConformationDetail::with('product')
         ->whereHas('purchaseConformation', function ($query) use ($request, $supplier_id) {
             $query->where('shipment_id', $request->shipment_id)
                   ->where('supplier_id', $supplier_id);
         })
         ->get();



     
        return view('shipment.supplier-report', compact('shipments','supplier','purchaseConformationDetail','PurchaseOrder'));
    }

    public function print(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);
        $shipments = Shipment::find($request->shipment_id);
    
        $PurchaseOrder = PurchaseOrder::where('supplier_id', $request->supplier_id)
                                      ->where('shipment_id', $request->shipment_id)
                                      ->get();
    
        $purchaseConformationDetail = PurchaseConformationDetail::with('product','purchaseConformation.shipment')
            ->whereHas('purchaseConformation', function ($query) use ($request) {
                $query->where('shipment_id', $request->shipment_id)
                      ->where('supplier_id', $request->supplier_id);
            })
            ->get();
            
    
        return view('shipment.supplier-report-print', compact('supplier', 'shipments', 'PurchaseOrder', 'purchaseConformationDetail'));
    }
    
    
    
    
    


}

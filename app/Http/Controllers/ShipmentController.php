<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseConformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;
use App\Models\Shipment;
use App\Models\Supplier;



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
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('shipment',Auth::user()->store_id=1);
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
                 
                     return redirect()->route('shipment.index')->with('success', 'Shipment status updated successfully.');
                 }
                 
                 
                 

}

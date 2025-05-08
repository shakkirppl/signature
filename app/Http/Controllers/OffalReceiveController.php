<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\Category;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\OffalReceive;

class OffalReceiveController extends Controller
{
    public function create()
    {
       
        $offalCategory = Category::whereRaw('LOWER(name) = ?', ['offal'])->first();

        
        $products = $offalCategory ? Product::where('category_id', $offalCategory->id)->get() : [];
    
        $shipments = Shipment::where('shipment_status', 0)->get();
    
        return view('offal-receive.create',['invoice_no'=>$this->invoice_no()], compact('shipments', 'products'));
    }

    public function index()
    {
        $offalReceives = OffalReceive::with('shipments','products')->get();
        return view('offal-receive.index', compact('offalReceives'));
    }


    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('offal_receive',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }


       //   return $request->all();
       public function store(Request $request)
       {
           // Validate request data
           $request->validate([
               'date' => 'required|date',
               'shipment_id' => 'required|exists:shipment,id',
               'product_id.*' => 'required|exists:product,id', // Validate multiple product IDs
               'qty.*' => 'required|numeric', // Validate multiple quantities
               'good_offal.*' => 'nullable|numeric', // Validate multiple good offal entries
               'damaged_offal.*' => 'nullable|numeric', // Validate multiple damaged offal entries
           ]);
       
           // Loop through each submitted product and save
           foreach ($request->product_id as $key => $product_id) {
               OffalReceive::create([
                   'order_no' => $request->order_no,
                   'date' => $request->date,
                   'shipment_id' => $request->shipment_id,
                   'product_id' => $product_id,
                   'qty' => $request->qty[$key],
                   'good_offal' => $request->good_offal[$key] ?? null,
                   'damaged_offal' => $request->damaged_offal[$key] ?? null,
                   'user_id' => Auth::id(),
                   'store_id' => 1,
               ]);
               InvoiceNumber::updateinvoiceNumber('offal_receive',1);
           }
       
           return redirect()->route('offal-receive.index')->with('success', 'Offal Receive created successfully.');
       }
       
            
    //    public function update(Request $request, $id)
    //    {
    //        // Validate request data
    //        $request->validate([
    //            'date' => 'required|date',
    //            'shipment_id' => 'required|exists:shipment,id',
    //            'product_id.*' => 'required|exists:product,id', // Validate multiple product IDs
    //            'qty.*' => 'required|numeric', // Validate multiple quantities
    //            'good_offal.*' => 'nullable|numeric', // Validate multiple good offal entries
    //            'damaged_offal.*' => 'nullable|numeric', // Validate multiple damaged offal entries
    //        ]);
       
    //        // Loop through each submitted product
    //        foreach ($request->product_id as $key => $product_id) {
    //            // Find the existing record for the given order and product
    //            $offalReceive = OffalReceive::where('order_no', $id)
    //                ->where('product_id', $product_id)
    //                ->first();
       
    //            if ($offalReceive) {
    //                // Update the existing record
    //                $offalReceive->update([
    //                    'date' => $request->date,
    //                    'shipment_id' => $request->shipment_id,
    //                    'qty' => $request->qty[$key],
    //                    'good_offal' => $request->good_offal[$key] ?? null,
    //                    'damaged_offal' => $request->damaged_offal[$key] ?? null,
    //                    'user_id' => Auth::id(),
    //                    'store_id' => 1,
    //                ]);
    //            } else {
    //                // Create a new record if it doesn't exist
    //                OffalReceive::create([
    //                    'order_no' => $id,
    //                    'date' => $request->date,
    //                    'shipment_id' => $request->shipment_id,
    //                    'product_id' => $product_id,
    //                    'qty' => $request->qty[$key],
    //                    'good_offal' => $request->good_offal[$key] ?? null,
    //                    'damaged_offal' => $request->damaged_offal[$key] ?? null,
    //                    'user_id' => Auth::id(),
    //                    'store_id' => 1,
    //                ]);
    //            }
    //        }
       
    //        return redirect()->route('offal-receive.index')->with('success', 'Offal Receive updated successfully.');
    //    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'shipment_id' => 'required|exists:shipment,id',
            'qty.*' => 'required|numeric', 
            'good_offal.*' => 'nullable|numeric', 
            'damaged_offal.*' => 'nullable|numeric', 
        ]);
    
        try {
            $offalReceive = OffalReceive::findOrFail($id);
    
            // Update the main record fields (if applicable)
            $offalReceive->update([
                'order_no' => $request->order_no,
                'date' => $request->date,
                'shipment_id' => $request->shipment_id,
                'user_id' => Auth::id(),
                'store_id' => 1,
            ]);
    
            // Loop through products and update related entries
            foreach ($request->product_id as $key => $product_id) {
                $offalReceive->updateOrCreate(
                    ['product_id' => $product_id], // Condition to check if the record exists
                    [
                        'qty' => $request->qty[$key],
                        'good_offal' => $request->good_offal[$key] ?? null,
                        'damaged_offal' => $request->damaged_offal[$key] ?? null,
                    ]
                );
            }
    
            return redirect()->route('offal-receive.index')->with('success', 'Updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    

       


    public function edit($id)
    {
        $offalReceive = OffalReceive::findOrFail($id);
        $offalCategory = Category::whereRaw('LOWER(name) = ?', ['offal'])->first();
        $products = $offalCategory ? Product::where('category_id', $offalCategory->id)->get() : [];
        $shipments = Shipment::where('shipment_status', 0)->get();

        return view('offal-receive.edit', compact('offalReceive', 'shipments', 'products'));
    }

    

    public function destroy($id)
    {
        $offalReceive = OffalReceive::findOrFail($id);
        
        $offalReceive->delete();
        InvoiceNumber::decreaseInvoice('offal_receive', 1); 
        return redirect()->route('offal-receive.index')->with('success', 'Offal Receive deleted successfully.');
    }

}

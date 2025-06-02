<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Airline;
use App\Models\BankMaster;
use App\Models\Localcustomer;
use App\Models\Shipment;
use App\Models\AccountHead;
use App\Models\Customer;
use App\Models\AccountTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AirlineController extends Controller
{
    public function index()
    {
        $airlinePayments = Airline::with(['shipment', 'customer'])->get();
        // dd($airlinePayments); 
        return view('airline-payment.index', compact('airlinePayments'));
    }


    



    public function create()
    {
        $customers = Customer::all(); 
        $shipments = Shipment::where('shipment_status', 0)->get(); 
        // Get parent IDs of "Expenses" and "Liabilities"
        $airlinesParent = AccountHead::where('name', 'AIRLINES')->first();
    $agentsParent = AccountHead::where('name', 'AIRLINES AGENT')->first();

    // Fetch subcategories (child accounts) under "AIRLINES" and "AIRLINES AGENT"
    $airlinesCOA = $airlinesParent ? AccountHead::where('parent_id', $airlinesParent->id)->get() : [];
    $agentsCOA = $agentsParent ? AccountHead::where('parent_id', $agentsParent->id)->get() : [];

    
        return view('airline-payment.create', [
            'invoice_no' => $this->invoice_no()
        ], compact('shipments', 'customers','airlinesCOA','agentsCOA'));
    }
    
    /**
     * Recursive function to fetch all subcategories of given parent IDs
     */
   


    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('airline_payment',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }



                 public function store(Request $request)
                 {
                     $request->validate([
                         'code' => 'required|string',
                         'date' => 'required|date',
                         'airline_name' => 'required|string',
                         'airline_number' => 'required|string',
                         'type' => 'required|string',
                         'shipment_id' => 'required|exists:shipment,id',
                         'customer_id' => 'required|exists:customer,id',
                         'coa_id' => 'required|exists:account_heads,id',
                         'air_waybill_no' => 'nullable|string',
                         'air_waybill_charge' => 'nullable|numeric',
                         'documents_charge' => 'nullable|numeric',
                         'amount' => 'required',
                         'description' => 'nullable|string',
                         'total_weight' => 'nullable|numeric',
                     ]);
                 
                     DB::beginTransaction();
                     try {
                         $airline = new Airline();
                         $airline->code = $request->code;
                         $airline->date = $request->date;
                         $airline->type = $request->type;
                         $airline->airline_name = $request->airline_name;
                         $airline->airline_number = $request->airline_number;
                         $airline->shipment_id = $request->shipment_id;
                         $airline->customer_id = $request->customer_id;
                         $airline->coa_id = $request->coa_id;
                         $airline->total_weight = $request->total_weight;
                         $airline->air_waybill_no = $request->air_waybill_no;
                         $airline->air_waybill_charge = $request->air_waybill_charge;
                         $airline->documents_charge = $request->documents_charge;
                         $airline->amount = str_replace(',', '', $request->amount);
                         $airline->description = $request->description;
                         $airline->user_id = Auth::id();
                         $airline->store_id = 1; 
                         $airline->currency = 'USD'; 
                         $airline->save();

                         InvoiceNumber::updateinvoiceNumber('airline_payment',1);

                         $group_no = AccountTransactions::orderBy('id','desc')->max('group_no');
                         $group_no+=1;
                         AccountTransactions::storeTransaction($group_no,$airline->date,"20",$airline->id,$airline->coa_id,"Airline Invoice No:".$airline->code,"Airline",$airline->amount ,null);
                         AccountTransactions::storeTransaction($group_no,$airline->date,$airline->coa_id,$airline->id,"20","Airline Invoice No:".$airline->code,"Airline",null,$airline->amount);



                         DB::commit(); // Commit transaction
                         return redirect()->route('airline.index')->with('success', 'Airline Payment recorded successfully!');
                 
                     } catch (\Exception $e) {
                         DB::rollBack(); // Rollback transaction on error
                         Log::error('Error storing Airline Payment: ' . $e->getMessage(), [
                             
                         ]);
                 
                         return redirect()->back()->with('error', 'Failed to record Airline Payment. Please try again.');
                     }
                 }
                 

                 public function edit($id)
                 {
                     $airline = Airline::findOrFail($id);
                     $shipments = Shipment::where('shipment_status', 0)->get(); 
                     $customers = Customer::all();
                     $airlinesParent = AccountHead::where('name', 'AIRLINES')->first();
                     $agentsParent = AccountHead::where('name', 'AIRLINES AGENT')->first();
                 
                     // Fetch subcategories (child accounts) under "AIRLINES" and "AIRLINES AGENT"
                     $airlinesCOA = $airlinesParent ? AccountHead::where('parent_id', $airlinesParent->id)->get() : [];
                     $agentsCOA = $agentsParent ? AccountHead::where('parent_id', $agentsParent->id)->get() : [];
                     
                   
                 
                     return view('airline-payment.edit', compact('airlinesCOA', 'shipments', 'customers', 'agentsCOA','airline'));
                 }
                 public function update(Request $request, $id)
                 {
                     $request->validate([
                         'code' => 'required|string',
                         'date' => 'required|date',
                         'airline_name' => 'required|string',
                         'airline_number' => 'required|string',
                         'shipment_id' => 'required|exists:shipment,id',
                         'customer_id' => 'required|exists:customer,id',
                         'coa_id' => 'required|exists:account_heads,id',
                         'air_waybill_no' => 'nullable|string',
                         'air_waybill_charge' => 'nullable|numeric',
                         'documents_charge' => 'nullable|numeric',
                         'amount' => 'required|numeric',
                         'description' => 'nullable|string',
                         'total_weight' => 'nullable|numeric',
                         'type' => 'required|string',
                        
                     ]);
                 
                     // Debugging: Check what is being sent
                     \Log::info('Update Request Data:', $request->all());
                 
                     $airline = Airline::findOrFail($id);
                 
                     // Debugging: Check existing data before updating
                     \Log::info('Existing Airline Data:', $airline->toArray());
                 
                     $airline->update($request->all());
                 
                     // Debugging: Check data after update
                     \Log::info('Updated Airline Data:', $airline->toArray());
                 
                     return redirect()->route('airline.index')->with('success', 'Airline Payment updated successfully!');
                 }
                 
                         

public function destroy($id)
{
    try {
        $airlinepayment = Airline::findOrFail($id);
        $airline->delete();
        InvoiceNumber::decreaseInvoice('airline_payment', 1);
        return redirect()->route('airline.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('airline.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}


public function softDelete($id)
{
    $airline = Airline::findOrFail($id);
    $airline->delete_status = 1;
    $airline->save();

    return redirect()->route('airline.index')->with('success', 'Deletion request submitted for admin approval.');
}


public function deletionRequests()
{
    $deletionRequests = Airline::where('delete_status', 1)->with(['shipment', 'customer', 'user'])->get();
    return view('airline-payment.pending-delete', compact('deletionRequests'));
}


public function adminDestroy($id)
{
    $airline = Airline::findOrFail($id);
    $airline->delete(); 
    return back()->with('success', 'Airline record permanently deleted.');
}


// Show edit request form for accountant (designation_id == 3)
public function editRequest($id)
{
     $user = auth()->user();
    if ($user->designation_id != 3) {
        return redirect()->back()->withErrors(['error' => 'Unauthorized']);
    }
  $airline = Airline::findOrFail($id);
                     $shipments = Shipment::where('shipment_status', 0)->get(); 
                     $customers = Customer::all();
                     $airlinesParent = AccountHead::where('name', 'AIRLINES')->first();
                     $agentsParent = AccountHead::where('name', 'AIRLINES AGENT')->first();
                 
                     // Fetch subcategories (child accounts) under "AIRLINES" and "AIRLINES AGENT"
                     $airlinesCOA = $airlinesParent ? AccountHead::where('parent_id', $airlinesParent->id)->get() : [];
                     $agentsCOA = $agentsParent ? AccountHead::where('parent_id', $agentsParent->id)->get() : [];

    return view('airline-payment.edit-request', compact('airlinesCOA', 'shipments', 'customers', 'agentsCOA','airline'));
}

public function submitEditRequest(Request $request, $id)
{
    $user = auth()->user();
    if ($user->designation_id != 3) {
        return redirect()->back()->withErrors(['error' => 'Unauthorized']);
    }

    $payment = Airline::findOrFail($id);

    $data = $request->validate([
        'code' => 'required|string',
        'date' => 'required|date',
        'airline_name' => 'required|string',
        'airline_number' => 'required|string',
        'shipment_id' => 'required|exists:shipment,id',
        'customer_id' => 'required|exists:customer,id',
        'coa_id' => 'required|exists:account_heads,id',
        'air_waybill_no' => 'nullable|string',
        'air_waybill_charge' => 'nullable|numeric',
        'documents_charge' => 'nullable|numeric',
        'amount' => 'required|numeric',
        'description' => 'nullable|string',
        'total_weight' => 'nullable|numeric',
    ]);

    $payment->edit_request_data = json_encode($data);
    $payment->edit_status = 'pending';
    $payment->save();

    return redirect()->route('airline.index')->with('success', 'Edit request submitted successfully!');
}


public function pendingEditRequests()
{
    $user = auth()->user();
    if ($user->designation_id != 1) {
        return redirect()->back()->withErrors(['error' => 'Unauthorized']);
    }

    $payments = Airline::where('edit_status', 'pending')->get();

    foreach ($payments as $payment) {
        $requestedData = json_decode($payment->edit_request_data, true);
        $changedFields = [];

        if (!$requestedData) continue;

        foreach ($requestedData as $field => $newValue) {
            $originalValue = $payment->$field;

            if (in_array($field, ['air_waybill_charge', 'documents_charge', 'amount', 'total_weight'])) {
                $originalValue = (float) $originalValue;
                $newValue = (float) $newValue;
            }

            if ($originalValue != $newValue) {
                $changedFields[$field] = [
                    'original' => $originalValue,
                    'requested' => $newValue,
                ];
            }
        }

        $payment->changed_fields = $changedFields;
    }

    return view('airline-payment.pending-edit-request', compact('payments'));
}


// Approve the edit request
public function approveEdit($id)
{
    $user = auth()->user();
    if ($user->designation_id != 1) {
        return redirect()->back()->withErrors(['error' => 'Unauthorized']);
    }

    $payment = Airline::findOrFail($id);

    if ($payment->edit_status !== 'pending' || !$payment->edit_request_data) {
        return redirect()->back()->withErrors(['error' => 'No pending edit request found.']);
    }

    $editData = json_decode($payment->edit_request_data, true);

    $payment->fill($editData);
    $payment->edit_status = 'approved';
    $payment->edit_request_data = null;
    $payment->save();

    return redirect()->route('airline.index')->with('success', 'Edit request approved and data updated.');
}

public function rejectEditRequest($id)
{
    $user = auth()->user();
    if ($user->designation_id != 1) {
        return redirect()->back()->withErrors(['error' => 'Unauthorized']);
    }

    $payment = Airline::findOrFail($id);

    if ($payment->edit_status === 'pending') {
        $payment->edit_status = 'rejected';
        $payment->edit_request_data = null;
        $payment->save();
    }

    return redirect()->back()->with('success', 'Edit request rejected successfully.');
}





}

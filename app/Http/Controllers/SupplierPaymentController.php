<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankMaster;
use App\Models\Supplier;
use App\Models\SupplierPaymentMaster;
use App\Models\SupplierPaymentDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseConformation;
use App\Models\Shipment;



class SupplierPaymentController extends Controller
{
    public function create()
    {
        $banks = BankMaster::all(); 
        $shipments = Shipment::whereIn('id', PurchaseConformation::pluck('shipment_id'))->get();
        
        return view('supplier-payment.create', compact('banks', 'shipments'));
    }
    
    
    public function getSuppliersByShipment(Request $request)
    {
        $shipmentId = $request->shipment_id;
    
        $suppliers = Supplier::whereIn('id', PurchaseConformation::where('shipment_id', $shipmentId)
                        ->pluck('supplier_id'))
                        ->get();
    
        return response()->json($suppliers);
    }
    
            // return $request->all();
            public function store(Request $request)
            {
                // return $request->all();
                
                $validatedData = $request->validate([
                    'payment_date' => 'required|date',
                    'shipment_id' => 'required',
                    'payment_type' => 'required|string',
                    'bank_name' => 'nullable|string',
                    'outstanding_amount' => 'required',
                    'allocated_amount' => 'required',
                    'total_paidAmount' => 'required',
                    'total_amount' => 'required',
                    'total_balance' => 'required',
                    'balance' => 'nullable',
                    'payment_to' => 'required|integer',
                    'notes' => 'nullable|string',
                    'trans_reference' => 'nullable|string',
                    'cheque_no' => 'nullable|string',
                    'cheque_date' => 'nullable|date',
                    'conformation_id.*' => 'required|exists:purchase_conformation,id',
                    'pi_no.*' => 'required|string',
                    'amount.*' => 'required',
                    'balance_amount.*' => 'required',
                    'paid.*' => 'required',
                ]);
              
                
                if ($validatedData['allocated_amount'] != $validatedData['total_paidAmount']) {
                    return redirect()->back()
                        ->withErrors(['error' => 'Allocated amount must be equal to the total paid amount.'])
                        ->withInput();
                }
            
                try {
                    $supplierPayment = SupplierPaymentMaster::create([
                        'payment_date' => $validatedData['payment_date'] ,
                        'shipment_id' => $validatedData['shipment_id'] ,
                        'payment_type' => $validatedData['payment_type'],
                        'bank_name' => $validatedData['bank_name'] ?? null,
                        'outstanding_amount' => $validatedData['outstanding_amount'],
                        'allocated_amount' => $validatedData['allocated_amount'],
                        'balance' => $validatedData['balance'] ?? 0,
                        'payment_to' => $validatedData['payment_to'],
                        'notes' => $validatedData['notes'] ?? null,
                        'trans_reference' => $validatedData['trans_reference'] ?? null,
                        'cheque_no' => $validatedData['cheque_no'] ?? null,
                        'cheque_date' => $validatedData['cheque_date'] ?? null,
                        'user_id' => Auth::id(),
                        'store_id' => 1,
                        'total_paid' => $validatedData['total_paidAmount'],
                        'total_amount' => $validatedData['total_amount'],
                        'total_balance' => $validatedData['total_balance'],
                
                    ]);
            
                    foreach ($validatedData['conformation_id'] as $index => $conformationId) {
                        $paidAmount = $validatedData['paid'][$index] ?? 0;
            
                        SupplierPaymentDetail::create([
                            'master_id' => $supplierPayment->id,
                            'conformation_id' => $conformationId,
                            'pi_no' => $validatedData['pi_no'][$index],
                            'amount' => $validatedData['amount'][$index],
                            'balance_amount' => $validatedData['balance_amount'][$index],
                            'paid' => $paidAmount,
                            'user_id' => Auth::id(),
                            'store_id' => 1,
                        ]);
            
                        $purchaseConformation = PurchaseConformation::find($conformationId);
                     if ($purchaseConformation) {
                        $newPaidAmount = $purchaseConformation->paid_amount + $paidAmount;
                        $newBalance = max(0, $purchaseConformation->balance_amount - $paidAmount);

       
                        $purchaseConformation->update([
                        'paid_amount' => $newPaidAmount,
                        'balance_amount' => $newBalance,
        ]);
                        }
                    }
            
                    return redirect()->route('supplier-payment.index')->with('success');
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])->withInput();
                }
            }
            
        
    
    
            public function index()
            {
                $supplierPayments = SupplierPaymentMaster::with('details', 'supplier', 'shipment')->get();
                return view('supplier-payment.index', compact('supplierPayments'));
            }
            
   

    public function view($id)
{
    $supplierPayment = SupplierPaymentMaster::with('details', 'supplier')->findOrFail($id); 
    return view('supplier-payment.view', compact('supplierPayment'));
}



public function destroy($id)
{
    try {
        $SupplierPaymentMaster = SupplierPaymentMaster::findOrFail($id);
        $SupplierPaymentMaster->delete();
        return redirect()->route('supplier-payment.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('supplier-payment.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}




public function getSupplierConformations(Request $request)
{
    $supplierId = $request->supplier_id;

    $conformations = DB::table('purchase_conformation_detail')
        ->join('purchase_conformation', 'purchase_conformation.id', '=', 'purchase_conformation_detail.conformation_id')
        ->where('purchase_conformation.supplier_id', $supplierId)
        ->where('purchase_conformation.balance_amount', '>', 0) 
        ->select(
            'purchase_conformation_detail.conformation_id', 
            'purchase_conformation.invoice_number', 
            'purchase_conformation.date',  
            'purchase_conformation.grand_total as total_amount', 
            'purchase_conformation.balance_amount'
        )
        ->distinct() // Avoid duplicate entries
        ->get();

    return response()->json($conformations);
}


public function report(Request $request)
{
    // Initialize query
    $query = SupplierPaymentMaster::with('supplier', 'details');

    // Apply date filters if provided
    if ($request->from_date && $request->to_date) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    // Filter by supplier if selected
    if ($request->supplier_id) {
        $query->where('payment_to', $request->supplier_id);
    }

    // Get the results
    $supplierPayments = $query->get();
    $suppliers = Supplier::all();

    return view('supplier-payment.report', compact('supplierPayments', 'suppliers'));
}




}

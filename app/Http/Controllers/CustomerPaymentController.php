<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerPaymentDetail;
use App\Models\SalesPayment;
use Illuminate\Support\Facades\Auth;
use App\Models\BankMaster;
use App\Models\Customer;




class CustomerPaymentController extends Controller
{
    
    public function create()
    {
        $banks = BankMaster::all(); 
        $customers = Customer::all();
        return view('customer-payment.create', compact('banks','customers'));
    }

    public function getCustomerSales(Request $request)
    {
        $customerId = $request->customer_id;
    
        $salesData = DB::table('sales_payment_master')
            ->where('sales_payment_master.customer_id', $customerId)
            ->join('sales_payment_detail', 'sales_payment_master.id', '=', 'sales_payment_detail.sales_payment_id')
            ->where('sales_payment_master.balance_amount', '>', 0) 
            ->select(
                'sales_payment_master.order_no',
                'sales_payment_master.id as sales_payment_id', // Ensure unique ID is selected
                'sales_payment_master.date',
                'sales_payment_master.grand_total',
                'sales_payment_master.balance_amount'
            )
            ->groupBy( // Prevent duplicate records
                'sales_payment_master.order_no',
                'sales_payment_master.id',
                'sales_payment_master.date',
                'sales_payment_master.grand_total',
                'sales_payment_master.balance_amount'
            )
            ->get();
    
        return response()->json($salesData);
    }
    

public function store(Request $request)
{
    // Debugging: Check the incoming request data
    // return $request->all();

    $validatedData = $request->validate([
        'payment_date' => 'required|date',
        'payment_type' => 'required|string',
        'bank_name' => 'nullable|string',
        'outstanding_amount' => 'required|numeric',
        'allocated_amount' => 'required|numeric',
        'total_paidAmount' => 'required|numeric',
        'total_amount' => 'required|numeric',
        'total_balance' => 'required|numeric',
        'balance' => 'nullable|numeric',
        'payment_to' => 'required|integer',
        'notes' => 'nullable|string',
        'trans_reference' => 'nullable|string',
        'cheque_no' => 'nullable|string',
        'cheque_date' => 'nullable|date',
        'pi_no.*' => 'required|string',
        'sales_payment_id.*' => 'required|exists:sales_payment_master,id',

        'amount.*' => 'required|numeric',
        'balance_amount.*' => 'required|numeric',
        'paid.*' => 'required|numeric|min:0',
    ]);

    if ($validatedData['allocated_amount'] != $validatedData['total_paidAmount']) {
        return redirect()->back()
            ->withErrors(['error' => 'Allocated amount must be equal to the total paid amount.'])
            ->withInput();
    }

    try {
        // Store customer payment in customer_payment_master table
        $customerPayment = CustomerPayment::create([
            'payment_date' => $validatedData['payment_date'],
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
        foreach ($validatedData['sales_payment_id'] as $index => $salesId) {
            $paidAmount = isset($validatedData['paid'][$index]) ? (float) $validatedData['paid'][$index] : 0;
        
               $customerPay =CustomerPaymentDetail::create([
                    'master_id' => $customerPayment->id,
                    'sales_payment_id' => $salesId,
                    'pi_no' => $validatedData['pi_no'][$index],
                    'amount' => $validatedData['amount'][$index],
                    'balance_amount' => $validatedData['balance_amount'][$index],
                    'paid' => $paidAmount,
                    'user_id' => Auth::id(),
                    'store_id' => 1,
                ]);
        
                

                $salesPayment = SalesPayment::find($salesId);
                if ($salesPayment) {
                    $newPaidAmount = $salesPayment->paid_amount + $paidAmount;
                    $newBalance = max(0, $salesPayment->balance_amount - $paidAmount);
        
                    $salesPayment->update([
                        'paid_amount' => $newPaidAmount,
                        'balance_amount' => $newBalance,
                    ]);
                }
            
        }
        return redirect()->route('customer-payment.index')->with('success', 'Payment saved successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])->withInput();
    }
}
      
        
    
    
    public function index()
    {
        $customerPayments = CustomerPayment::with('details', 'customer')->get(); 
        return view('customer-payment.index', compact('customerPayments'));
    }

   
    public function report(Request $request)
    {
        // Fetch customers for the dropdown
        $customers = Customer::all();
    
        // Initialize query
        $query = CustomerPayment::with('customer', 'details');
    
        // Apply date filters if provided
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
        }
    
        // Filter by customer if selected
        if ($request->customer_id) {
            $query->where('payment_to', $request->customer_id);
        }
    
        // Get the results
        $customerPayments = $query->get();
    
        return view('customer-payment.report', compact('customerPayments', 'customers'));
    }

    public function view($id)
    {
        $customerPayment = CustomerPayment::with('details', 'customer')->findOrFail($id); 
        return view('customer-payment.view', compact('customerPayment'));
    }
    
 

}

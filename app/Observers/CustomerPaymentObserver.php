<?php

namespace App\Observers;

use App\Models\CustomerPayment;
use App\Models\Outstanding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerPaymentObserver
{
    /**
     * Handle the CustomerPayment "created" event.
     *
     * @param  \App\Models\CustomerPayment  $customerPayment
     * @return void
     */
    public function created(CustomerPayment $customerPayment)
    {
        Outstanding::create([
            'date' => $customerPayment->payment_date,
            'time' => Carbon::now()->format('H:i:s'),
            'account_id' => $customerPayment->payment_to,
            'receipt' => $customerPayment->total_paid, 
            'payment' => null,
            'narration' => null,
            'transaction_id' => $customerPayment->id,
            'transaction_type' => 'Customer Payment',
            'description' => 'Payment received from customer',
            'account_type' => 'customer',
            'store_id' => $customerPayment->store_id,
            'user_id' => Auth::id(),
            'financial_year' => date('Y'),
        ]);
    }

    /**
     * Handle the CustomerPayment "updated" event.
     *
     * @param  \App\Models\CustomerPayment  $customerPayment
     * @return void
     */
    public function updated(CustomerPayment $customerPayment)
    {
        
    }

    /**
     * Handle the CustomerPayment "deleted" event.
     *
     * @param  \App\Models\CustomerPayment  $customerPayment
     * @return void
     */
    public function deleted(CustomerPayment $customerPayment)
    {
        // Delete related outstanding records
        Outstanding::where('transaction_id', $customerPayment->id)
            ->where('transaction_type', 'Customer Payment')
            ->delete();
    }
    

    /**
     * Handle the CustomerPayment "restored" event.
     *
     * @param  \App\Models\CustomerPayment  $customerPayment
     * @return void
     */
    public function restored(CustomerPayment $customerPayment)
    {
        //
    }

    /**
     * Handle the CustomerPayment "force deleted" event.
     *
     * @param  \App\Models\CustomerPayment  $customerPayment
     * @return void
     */
    public function forceDeleted(CustomerPayment $customerPayment)
    {
        //
    }
}

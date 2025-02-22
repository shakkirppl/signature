<?php

namespace App\Observers;


use App\Models\SalesPayment;
use App\Models\Outstanding;

class SalesObserver
{
    /**
     * Handle the SalesPayment "created" event.
     *
     * @param  \App\Models\SalesPayment  $salesPayment
     * @return void
     */
    public function created(SalesPayment $salesPayment)
    {
        Outstanding::create([
            'date' => $salesPayment->date,
            'time' => now()->format('H:i:s'),
            'account_id' => $salesPayment->customer_id,
            'receipt' => null,
            'payment' => $salesPayment->grand_total,
            'narration' => null,
            'transaction_id' => $salesPayment->id,
            'transaction_type' => 'sales ',
            'description' => 'Sales Payment for Order No: ' . $salesPayment->order_no,
            'account_type' => 'customer',
            'store_id' => $salesPayment->store_id,
            'user_id' => $salesPayment->user_id,
            'financial_year' => date('Y'),
        ]);
    }

    /**
     * Handle the SalesPayment "updated" event.
     *
     * @param  \App\Models\SalesPayment  $salesPayment
     * @return void
     */
    public function updated(SalesPayment $salesPayment)
    {
        $outstanding = Outstanding::where('transaction_id', $salesPayment->id)
        ->where('transaction_type', 'sales')
        ->first();

       if ($outstanding) {
       $outstanding->update([
       'date' => $salesPayment->date,
       'time' => now()->format('H:i:s'),
       'account_id' => $salesPayment->customer_id,
       'receipt' => null,
       'payment' => $salesPayment->grand_total,
       'narration' => 'Updated Sales Payment for Order No: ' . $salesPayment->order_no,
       'description' => 'Sales Payment updated',
       'account_type' => 'customer',
       'store_id' => $salesPayment->store_id,
       'user_id' => $salesPayment->user_id,
        'financial_year' => date('Y'),
]);
}
    }

    /**
     * Handle the SalesPayment "deleted" event.
     *
     * @param  \App\Models\SalesPayment  $salesPayment
     * @return void
     */
    public function deleted(SalesPayment $salesPayment)
    {
        //
    }

    /**
     * Handle the SalesPayment "restored" event.
     *
     * @param  \App\Models\SalesPayment  $salesPayment
     * @return void
     */
    public function restored(SalesPayment $salesPayment)
    {
        //
    }

    /**
     * Handle the SalesPayment "force deleted" event.
     *
     * @param  \App\Models\SalesPayment  $salesPayment
     * @return void
     */
    public function forceDeleted(SalesPayment $salesPayment)
    {
        //
    }
}

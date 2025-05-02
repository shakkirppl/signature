<?php

namespace App\Observers;

use App\Models\SalesOrder;

use App\Models\Outstanding;

class SalesOrderObserver
{
    /**
     * Handle the SalesOrder "created" event.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return void
     */
    public function created(SalesOrder $salesOrder)
    {
        Outstanding::create([
            'date' => $salesOrder->date,
            'time' => now()->format('H:i:s'),
            'account_id' => $salesOrder->customer_id, 
            'receipt' => $salesOrder->advance_amount, 
            'payment' => null, 
            'narration' => null,
            'transaction_id' => $salesOrder->id,
            'transaction_type' => 'Sales Order',
            'description' => 'Sales Order #' . $salesOrder->order_no,
            'account_type' => 'customer', 
            'store_id' => $salesOrder->store_id,
            'user_id' => $salesOrder->user_id,
            'financial_year' => date('Y'), 
        ]);
    }

    /**
     * Handle the SalesOrder "updated" event.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return void
     */
    public function updated(SalesOrder $salesOrder)
    {
        $outstanding = Outstanding::where('transaction_id', $salesOrder->id)
        ->where('transaction_type', 'Sales Order')
        ->first();

              if ($outstanding) {
                       $outstanding->update([
                           'date' => $salesOrder->date,
                           'account_id' => $salesOrder->customer_id, 
                           'receipt' => $salesOrder->advance_amount, 
                           'description' => 'Sales Order #' . $salesOrder->order_no,

               ]);
         }

    }

    /**
     * Handle the SalesOrder "deleted" event.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return void
     */
    public function deleted(SalesOrder $salesOrder)
{
    Outstanding::where('transaction_id', $salesOrder->id)
        ->where('transaction_type', 'Sales Order')
        ->delete();
}

    /**
     * Handle the SalesOrder "restored" event.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return void
     */
    public function restored(SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Handle the SalesOrder "force deleted" event.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return void
     */
    public function forceDeleted(SalesOrder $salesOrder)
    {
        //
    }
}

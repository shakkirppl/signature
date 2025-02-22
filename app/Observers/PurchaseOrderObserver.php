<?php

namespace App\Observers;

use App\Models\PurchaseOrder;
use App\Models\Outstanding;
use Carbon\Carbon;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "created" event.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function created(PurchaseOrder $purchaseOrder)
    {
        Outstanding::create([
            'date' => $purchaseOrder->date,
            'time' => Carbon::now()->format('H:i:s'),
            'account_id' => $purchaseOrder->supplier_id, 
            'receipt' => null, 
            'payment' => $purchaseOrder->advance_amount, 
            'narration' => null,
            'transaction_id' => $purchaseOrder->id,
            'transaction_type' => 'Purchase Order',
            'description' => 'Purchase Order Created - ' . $purchaseOrder->order_no,
            'account_type' => 'supplier', 
            'store_id' => $purchaseOrder->store_id,
            'user_id' => $purchaseOrder->user_id,
            'financial_year' => date('Y'), 
        ]);
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function updated(PurchaseOrder $purchaseOrder)
    {
        $outstanding = Outstanding::where('transaction_id', $purchaseOrder->id)
        ->where('transaction_type', 'Purchase Order')
        ->first();

    if ($outstanding) {
        $outstanding->update([
            'date' => $purchaseOrder->date,
            'account_id' => $purchaseOrder->supplier_id, // Updating the account_id
            'payment' => $purchaseOrder->advance_amount, 
            'description' => 'Purchase Order Updated - ' . $purchaseOrder->order_no,
           
        ]);
    }

    }

    /**
     * Handle the PurchaseOrder "deleted" event.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function deleted(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "restored" event.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function restored(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "force deleted" event.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function forceDeleted(PurchaseOrder $purchaseOrder)
    {
        //
    }
}

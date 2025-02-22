<?php

namespace App\Observers;

use App\Models\PurchaseConformation;
use App\Models\Outstanding;

class PurchaseConformationObserver
{
    /**
     * Handle the PurchaseConformation "created" event.
     *
     * @param  \App\Models\PurchaseConformation  $purchaseConformation
     * @return void
     */
    public function created(PurchaseConformation $purchaseConformation)
    {
        Outstanding::create([
            'date' => now(),
            'time' => now()->format('H:i:s'),
            'account_id' => $purchaseConformation->supplier_id,
            'receipt' => $purchaseConformation->item_total,
            'payment' => null,
            'narration' => 'Purchase Confirmation Created - Invoice ' . $purchaseConformation->invoice_number,
            'transaction_id' => $purchaseConformation->id,
            'transaction_type' => 'purchase conformation',
            'description' => 'Purchase Confirmation for Order ' . $purchaseConformation->order_no,
            'account_type' => 'supplier',
            'store_id' => $purchaseConformation->store_id,
            'user_id' => $purchaseConformation->user_id,
            'financial_year' => date('Y'),
        ]);
    }

    /**
     * Handle the PurchaseConformation "updated" event.
     *
     * @param  \App\Models\PurchaseConformation  $purchaseConformation
     * @return void
     */
    public function updated(PurchaseConformation $purchaseConformation)
    {
        Outstanding::where('transaction_id', $purchaseConformation->id)
        ->where('transaction_type', 'purchase conformation')
        ->delete();

    $this->storeOutstandingEntry($purchaseConformation);
    }

    /**
     * Handle the PurchaseConformation "deleted" event.
     *
     * @param  \App\Models\PurchaseConformation  $purchaseConformation
     * @return void
     */
    public function deleted(PurchaseConformation $purchaseConformation)
    {
        //
    }

    /**
     * Handle the PurchaseConformation "restored" event.
     *
     * @param  \App\Models\PurchaseConformation  $purchaseConformation
     * @return void
     */
    public function restored(PurchaseConformation $purchaseConformation)
    {
        //
    }

    /**
     * Handle the PurchaseConformation "force deleted" event.
     *
     * @param  \App\Models\PurchaseConformation  $purchaseConformation
     * @return void
     */
    public function forceDeleted(PurchaseConformation $purchaseConformation)
    {
        //
    }

    private function storeOutstandingEntry(PurchaseConformation $purchaseConformation)
    {
        Outstanding::create([
            'date' => now(),
            'time' => now()->format('H:i:s'),
            'account_id' => $purchaseConformation->supplier_id,
            'receipt' => $purchaseConformation->item_total,
            'payment' => null,
            'narration' => 'Purchase Confirmation - Invoice ' . $purchaseConformation->invoice_number,
            'transaction_id' => $purchaseConformation->id,
            'transaction_type' => 'purchase conformation',
            'description' => 'Purchase Confirmation for Order ' . $purchaseConformation->order_no,
            'account_type' => 'supplier',
            'store_id' => $purchaseConformation->store_id,
            'user_id' => $purchaseConformation->user_id,
            'financial_year' => date('Y'),
        ]);
    }
}

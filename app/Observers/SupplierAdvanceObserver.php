<?php

namespace App\Observers;

use App\Models\SupplierAdvance;
use App\Models\Outstanding;
use Illuminate\Support\Facades\Auth;

class SupplierAdvanceObserver
{
    /**
     * Handle the SupplierAdvance "created" event.
     *
     * @param  \App\Models\SupplierAdvance  $supplierAdvance
     * @return void
     */
    public function created(SupplierAdvance $supplierAdvance)
    {
        Outstanding::create([
            'date' => $supplierAdvance->date,
            'time' => now()->format('H:i:s'), // Current time
            'account_id' => $supplierAdvance->supplier_id,
            'receipt' =>0, // Storing advance amount as receipt
            'payment' =>  $supplierAdvance->advance_amount, 
            'narration' => 'Supplier Advance Payment',
            'transaction_id' => $supplierAdvance->id, // Linking to supplier advance ID
            'transaction_type' => 'Supplier Advance',
            'description' => $supplierAdvance->description,
            'account_type' => 'Supplier',
            'store_id' => 1,
            'user_id' => Auth::id(),
            'financial_year' => date('Y'), 
        ]);
    }

    /**
     * Handle the SupplierAdvance "updated" event.
     *
     * @param  \App\Models\SupplierAdvance  $supplierAdvance
     * @return void
     */
    public function updated(SupplierAdvance $supplierAdvance)
    {
        //
    }

    /**
     * Handle the SupplierAdvance "deleted" event.
     *
     * @param  \App\Models\SupplierAdvance  $supplierAdvance
     * @return void
     */
    public function deleted(SupplierAdvance $supplierAdvance)
    {
        Outstanding::where('transaction_id', $supplierAdvance->id)
            ->where('transaction_type', 'Supplier Advance')
            ->delete();
    }

    /**
     * Handle the SupplierAdvance "restored" event.
     *
     * @param  \App\Models\SupplierAdvance  $supplierAdvance
     * @return void
     */
    public function restored(SupplierAdvance $supplierAdvance)
    {
        //
    }

    /**
     * Handle the SupplierAdvance "force deleted" event.
     *
     * @param  \App\Models\SupplierAdvance  $supplierAdvance
     * @return void
     */
    public function forceDeleted(SupplierAdvance $supplierAdvance)
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\SupplierPaymentMaster;

use App\Models\SupplierPaymentDetail;
use App\Models\Outstanding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupplierPaymentObserver
{
    /**
     * Handle the SupplierPaymentMaster "created" event.
     *
     * @param  \App\Models\SupplierPaymentMaster  $supplierPaymentMaster
     * @return void
     */
    public function created(SupplierPaymentMaster $supplierPayment)
    {
        Outstanding::create([
            'date' => $supplierPayment->payment_date,
            'time' => now()->format('H:i:s'),
            'account_id' => $supplierPayment->payment_to, 
            'receipt' => null, 
            'payment' => $supplierPayment->total_paid, 
            'narration' => null,
            'transaction_id' => $supplierPayment->id,
            'transaction_type' => 'Supplier Payment', 
            'description' => $supplierPayment->notes ?? 'Payment made to supplier',
            'account_type' => 'supplier', 
            'store_id' => $supplierPayment->store_id,
            'user_id' => $supplierPayment->user_id,
            'financial_year' => date('Y'), 
        ]);
    
    }

    /**
     * Handle the SupplierPaymentMaster "updated" event.
     *
     * @param  \App\Models\SupplierPaymentMaster  $supplierPaymentMaster
     * @return void
     */
    public function updated(SupplierPaymentMaster $supplierPayment)
    {
        //
    }

    /**
     * Handle the SupplierPaymentMaster "deleted" event.
     *
     * @param  \App\Models\SupplierPaymentMaster  $supplierPaymentMaster
     * @return void
     */
    public function deleted(SupplierPaymentMaster $supplierPayment)
    {
        //
    }

    /**
     * Handle the SupplierPaymentMaster "restored" event.
     *
     * @param  \App\Models\SupplierPaymentMaster  $supplierPaymentMaster
     * @return void
     */
    public function restored(SupplierPaymentMaster $supplierPayment)
    {
        //
    }

    /**
     * Handle the SupplierPaymentMaster "force deleted" event.
     *
     * @param  \App\Models\SupplierPaymentMaster  $supplierPaymentMaster
     * @return void
     */
    public function forceDeleted(SupplierPaymentMaster $supplierPayment)
    {
        //
    }
}

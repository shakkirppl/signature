<?php

namespace App\Observers;

use App\Models\OpeningBalance;
use App\Models\Outstanding;
use Carbon\Carbon;

class OpeningBalanceObserver
{
    /**
     * Handle the OpeningBalance "created" event.
     *
     * @param  \App\Models\OpeningBalance  $openingBalance
     * @return void
     */
    public function created(OpeningBalance $openingBalance)
    {
        Outstanding::create([
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
            'account_id' => $openingBalance->account_id,
            'receipt' => $openingBalance->dr_cr === 'Dr' ? $openingBalance->opening_balance : 0,
            'payment' => $openingBalance->dr_cr === 'Cr' ? $openingBalance->opening_balance : 0,
            'narration' => null,
            'transaction_id' => $openingBalance->id, 
            'transaction_type' => 'Opening Balance',
            'description' => 'Opening balance recorded for account',
            'account_type' => $openingBalance->account_type,
            'store_id' => $openingBalance->store_id,
            'user_id' => $openingBalance->user_id,
            'financial_year' => date('Y'),
        ]);
    }

    /**
     * Handle the OpeningBalance "updated" event.
     *
     * @param  \App\Models\OpeningBalance  $openingBalance
     * @return void
     */
    public function updated(OpeningBalance $openingBalance)
    {
        Outstanding::where('transaction_id', $openingBalance->id)->update([
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
            'receipt' => $openingBalance->dr_cr === 'Cr' ? $openingBalance->opening_balance : 0,
            'payment' => $openingBalance->dr_cr === 'Dr' ? $openingBalance->opening_balance : 0,
            'narration' => null,
            'description' => 'Opening balance recorded for account',
            'store_id' => $openingBalance->store_id,
            'user_id' => $openingBalance->user_id,
        ]);
    }

    /**
     * Handle the OpeningBalance "deleted" event.
     *
     * @param  \App\Models\OpeningBalance  $openingBalance
     * @return void
     */
    public function deleted(OpeningBalance $openingBalance)
    {
        //
    }

    /**
     * Handle the OpeningBalance "restored" event.
     *
     * @param  \App\Models\OpeningBalance  $openingBalance
     * @return void
     */
    public function restored(OpeningBalance $openingBalance)
    {
        //
    }

    /**
     * Handle the OpeningBalance "force deleted" event.
     *
     * @param  \App\Models\OpeningBalance  $openingBalance
     * @return void
     */
    public function forceDeleted(OpeningBalance $openingBalance)
    {
        //
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransactions extends Model
{
    protected $table = 'account_transactions';
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'group_no',
        'date',
        'client_id',
        'dr',
        'cr',
        'narration',
        'transaction_id',
        'client_id1',
        'description',
        'transaction_type',
        'user_id',
        'financial_year',
    ];
    protected $dates = ['deleted_at']; 
    public static function storeTransaction($group_no, $date, $client_id, $transaction_id, $client_id1, $description, $transaction_type, $dr = null, $cr = null,$narration=null){
        
        try {  
            $account_transactions = new AccountTransactions;
            
            $account_transactions->group_no = $group_no;
            $account_transactions->date = $date;
            $account_transactions->client_id = $client_id;
            $account_transactions->dr = $dr;
            $account_transactions->cr = $cr;
            $account_transactions->transaction_id = $transaction_id;
            $account_transactions->transaction_type = $transaction_type;
            $account_transactions->client_id1 = $client_id1;
            $account_transactions->description = $description;
            $account_transactions->narration = $narration;
            $account_transactions->user_id =1;
            $account_transactions->save();
  } catch (\Exception $e) {
  
return $e->getMessage();
}
}

}

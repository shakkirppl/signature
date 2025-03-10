<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptVoucher extends Model
{
    use HasFactory;
    protected $table = 'receipt_voucher';
    protected $fillable = ['code', 'date','name','customer_id','type','amount','description','bank_id','store_id','user_id'];

    


    public function bank()
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'id');
    }

    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id', 'id');
}

public function account()
    {
        return $this->belongsTo(AccountHead::class, 'coa_id');
    }  

}

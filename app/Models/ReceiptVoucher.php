<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptVoucher extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'receipt_voucher';
    protected $fillable = ['code', 'date','name','customer_id','type','amount','description','bank_id','store_id','user_id','currency'];

    


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
    protected $dates = ['deleted_at']; 

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

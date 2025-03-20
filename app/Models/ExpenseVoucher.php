<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseVoucher extends Model
{
    use HasFactory;

    protected $table = 'expense_voucher';
    protected $fillable = ['code', 'date','name','coa_id','type','amount','description','bank_id','store_id','user_id','shipment_id','status','currency'];


   

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function account()
    {
        return $this->belongsTo(AccountHead::class, 'coa_id');
    } 
    public function bank()
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'id');
    }
}

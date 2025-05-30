<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPayment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'customer_payment_master';
    protected $fillable = ['id', 'payment_date','bank_name','payment_type','cheque_date','cheque_no','trans_reference','outstanding_amount','allocated_amount','balance','payment_to','notes','user_id','store_id','total_balance','total_paid','total_amount','status'];

    protected $dates = ['deleted_at']; 


    public function details()
    {
        return $this->hasMany(CustomerPaymentDetail::class, 'master_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'payment_to');
    }
    
}

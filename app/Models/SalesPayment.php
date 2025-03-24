<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesPayment extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sales_payment_master';
    protected $fillable = [ 
    'order_no',
    'date',
    'sales_no',
    'customer_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'status',
    'store_id',
    'user_id',
    'paid_amount',
    'shipping_mode',
    'shipping_agent',
'shrinkage'];
protected $dates = ['deleted_at'];

    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    

    public function details()
    {
        return $this->hasMany(SalesPaymentDetail::class, 'sales_payment_id');
    }

    public function salesOrder()
{
    return $this->belongsTo(SalesOrder::class, 'sales_no');
}
}

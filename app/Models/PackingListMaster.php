<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackingListMaster extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'packing_list_masters';
    protected $fillable = [
        'packing_no', 'date', 'salesOrder_id', 'customer_id', 'shipping_mode', 
        'shipping_agent', 'terms_of_delivery', 'terms_of_payment', 'currency', 
        'sum_total', 'net_weight', 'gross_weight','store_id','user_id',
    ];

    protected $dates = ['deleted_at'];

    public function details()
    {
        return $this->hasMany(PackingListDetail::class, 'packing_master_id');
    }
   
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function SalesOrder()
    {
        return $this->belongsTo(Customer::class,'salesOrder_id');
    }
    
}

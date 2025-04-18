<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OffalSales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'offal_sales_master';
    protected $fillable = [ 
    'order_no',
    'date',
    'shipment_id',
    'lo_customer_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'status',
    'store_id',
    'user_id',];

    protected $dates = ['deleted_at'];

    
    public function localcustomer()
    {
        return $this->belongsTo(Localcustomer::class, 'lo_customer_id');
    }

   

    public function details()
    {
        return $this->hasMany(OffalSalesDetail::class, 'offal_sales_id');
    }
      
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

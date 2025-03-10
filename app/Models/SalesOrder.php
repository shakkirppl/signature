<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_order';
    protected $fillable = [ 
    'order_no',
    'date',
    'customer_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'store_id',
    'user_id',];


    


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
   

    public function Detail()
    {
        return $this->hasMany(SalesOrderDetail::class);
    }


    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id');
    }

    // Automatically delete associated details when a SalesOrder is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($salesOrder) {
            // Delete all related SalesOrderDetails
            $salesOrder->details()->delete();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'sales_order';
    protected $fillable = [ 
    'order_no',
    'date',
    'customer_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'store_id',
    'user_id',
    'delete_status',
'edit_status','edit_request_data'];


    protected $dates = ['deleted_at']; 


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
     public function products()
    {
        return $this->belongsTo(Product::class);
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

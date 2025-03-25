<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'purchase_order';
    protected $fillable = [ 
    'order_no',
    'date',
    'supplier_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'status',
    'inspection_status',
    'store_id',
    'user_id',
    'shipment_id',
    'SalesOrder_id'
];

protected $dates = ['deleted_at'];
  


    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    
   

    public function Detail()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }


    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
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

    public function products()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }

    public function shipment() {
        return $this->belongsTo(Shipment::class, 'shipment_id'); 
    }
    
    
    public function salesOrder() {
        return $this->belongsTo(SalesOrder::class, 'SalesOrder_id');
    }
    public function shipments()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function salesPayment()
{
    return $this->hasOne(SalesPayment::class, 'sales_no', 'SalesOrder_id');
}

    
}

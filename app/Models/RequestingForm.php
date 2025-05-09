<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestingForm extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'requesting_form';
    protected $fillable = [
        'invoice_no', 'order_no', 'date', 'supplier_id', 'shipment_id', 'SalesOrder_id',
        'ssf_no', 'market', 'advance_amount', 'bank_name', 'account_name',
        'account_no', 'user_id','supplier_no'
    ];

  


    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    
   

    public function Detail()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
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
public function purchaseOrder()
{
    return $this->hasOne(PurchaseOrder::class);
}
   
}



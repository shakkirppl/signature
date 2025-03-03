<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;
    protected $table = 'inspection';
    protected $fillable = ['purchaseOrder_id','order_no', 'date', 'supplier_id','store_id','user_id','status','purchase_status','shipment_id'];


    public function details()
    {
        return $this->hasMany(InspectionDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_no', 'order_no');
    }
    

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}

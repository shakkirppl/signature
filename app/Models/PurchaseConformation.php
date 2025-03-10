<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseConformation extends Model
{
    use HasFactory;

    protected $table = 'purchase_conformation';
    protected $fillable = ['weight_id','inspection_id','purchaseOrder_id', 'weight_code','date','supplier_id','grand_total','advance_amount','balance_amount','status','user_id','store_id','shipment_id','invoice_number','paid_amount','item_total','total_expense'];




    public function details()
{
    return $this->hasMany(PurchaseConformationDetail::class, 'conformation_id');
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

    public function shipment()
{
    return $this->belongsTo(Shipment::class, 'shipment_id');
}



}

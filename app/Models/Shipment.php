<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table = 'shipment';
    protected $fillable = ['date', 'time', 'shipment_no','shipment_status'];



  

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function weightCalculatorMaster()
{
    return $this->hasMany(WeightCalculatorMaster::class, 'shipment_id', 'id');
}



public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class, 'shipment_id'); // Assuming shipment_id is the foreign key
}
  
}

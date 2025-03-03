<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightCalculatorMaster extends Model
{
    use HasFactory;
    
    protected $table = 'weight_calculator_master';
    protected $fillable = ['id', 'date','weight_code','shipment_id','total_weight','user_id','store_id','status','supplier_id' ,'inspection_id','purchaseOrder_id'];


    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(WeightCalculatorDetail::class, 'weight_master_id'); // Explicitly define the foreign key
    }
    
}

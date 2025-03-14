<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightCalculatorDetail extends Model
{
    use HasFactory;
    protected $table = 'weight_calculator_detail';
    protected $fillable = ['id', 'weight_master_id','shipment_id','product_id','quandity','weight','supplier_id','male_accepted_qty','female_accepted_qty','total_accepted_qty',];



    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function weightCalculatorMaster()
{
    return $this->belongsTo(WeightCalculatorMaster::class, 'weight_master_id');
}


public function supplier()
{
    return $this->belongsTo(Supplier::class, 'supplier_id');
}


}



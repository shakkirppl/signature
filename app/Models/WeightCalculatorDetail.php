<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightCalculatorDetail extends Model
{
    use HasFactory;
    protected $table = 'weight_calculator_detail';
    protected $fillable = ['id', 'weight_master_id','shipment_id','product_id','quandity','weight','supplier_id'];



    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}



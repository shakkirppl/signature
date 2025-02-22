<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffalSalesDetail extends Model
{
    use HasFactory;

    protected $table = 'offal_sales_detail';
    protected $fillable = [ 
        'offal_sales_id',
        'product_id',
        'qty',
        'rate',
        'total',
        'store_id',
        
];


public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
}

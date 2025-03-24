<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OffalSalesDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'offal_sales_detail';
    protected $fillable = [ 
        'offal_sales_id',
        'product_id',
        'qty',
        'rate',
        'total',
        'store_id',
        
];
protected $dates = ['deleted_at'];


public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
}

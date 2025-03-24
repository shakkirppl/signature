<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sales_order_detail';
    protected $fillable = [ 
        'sales_order_id',
        'product_id',
        'qty',
        'rate',
        'total',
        'store_id',
        
];
protected $dates = ['deleted_at'];


public function product()
{
    return $this->belongsTo(Product::class);
}
}

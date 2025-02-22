<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'sales_order_detail';
    protected $fillable = [ 
        'sales_order_id',
        'product_id',
        'qty',
        'rate',
        'total',
        'store_id',
        
];


public function product()
{
    return $this->belongsTo(Product::class);
}
}

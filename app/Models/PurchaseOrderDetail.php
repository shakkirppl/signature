<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_detail';
    protected $fillable = [ 
        'purchase_order_id',
        'product_id',
        'type',
        'mark',
        'qty',
        'rate',
        'total',
        'store_id',
        
];


public function product()
{
    return $this->belongsTo(Product::class ,'product_id');
}
}

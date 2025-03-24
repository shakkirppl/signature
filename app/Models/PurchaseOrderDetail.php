<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'purchase_order_detail';
    protected $fillable = [ 
        'purchase_order_id',
        'product_id',
        'type',
        
        'qty',
        'rate',
        'total',
        'male',
        'female',
        'store_id',
        
];
protected $dates = ['deleted_at'];


public function product()
{
    return $this->belongsTo(Product::class ,'product_id');
}
}

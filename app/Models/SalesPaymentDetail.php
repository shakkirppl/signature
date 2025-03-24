<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesPaymentDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sales_payment_detail';
    protected $fillable = [ 
        'sales_payment_id',
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

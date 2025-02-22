<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'sales_payment_detail';
    protected $fillable = [ 
        'sales_payment_id',
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

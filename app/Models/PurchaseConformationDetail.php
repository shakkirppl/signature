<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseConformationDetail extends Model
{
    use HasFactory;
    protected $table = 'purchase_conformation_detail';
    protected $fillable = ['conformation_id', 'product_id','total_accepted_qty','rate','total','store_id','type','mark','total_weight','transportation_amount'];
   
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

public function conformation()
{
    return $this->belongsTo(PurchaseConformation::class, 'conformation_id');
}

}

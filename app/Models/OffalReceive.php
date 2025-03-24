<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OffalReceive extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'offal_receives';

    protected $fillable = [
        'order_no',
        'date',
        'shipment_id',
        'product_id',
        'qty',
        'good_offal',
        'damaged_offal',
        'store_id',
        'user_id'
    ];

    protected $dates = ['deleted_at']; 

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function shipments()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
    

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ReturnAmount extends Model
{
  
    use HasFactory,SoftDeletes;
    protected $table = 'return_payment';
    protected $fillable = [
        'date', 'shipment_id', 'supplier_id', 'retrun_amount','store_id','user_id'
    ];

   
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}


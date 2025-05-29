<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnToSupplier extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'return_to_supplier_payment';
    protected $fillable = [
        'date', 'supplier_id', 'retrun_amount','store_id','user_id','delete_status'
    ];

   
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}

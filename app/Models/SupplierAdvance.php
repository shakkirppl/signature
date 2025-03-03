<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAdvance extends Model
{
    use HasFactory;

    
    protected $table = 'supplier_advance';
    protected $fillable = ['code', 'date','shipment_id','supplier_id','type','order_no','advance_amount','bank_id','store_id','user_id','description'];

    
    public function bank()
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'id');
    }
}

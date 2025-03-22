<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierAdvance extends Model
{
    use HasFactory,SoftDeletes;

    
    protected $table = 'supplier_advance';
    protected $fillable = ['code', 'date','shipment_id','supplier_id','type','order_no','advance_amount','bank_id','store_id','user_id','description','purchaseOrder_id'];

    
    public function bank()
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'id');
    }
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    protected $dates = ['deleted_at']; 
    
}

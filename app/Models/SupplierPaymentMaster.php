<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPaymentMaster extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'supplier_payment_master';
    protected $fillable = ['id', 'payment_date','bank_name','payment_type','cheque_date','cheque_no','trans_reference','outstanding_amount','allocated_amount','balance','payment_to','notes','user_id','store_id','total_balance','total_paid','total_amount','shipment_id','delete_status'];

protected $dates = ['deleted_at']; 

    public function details()
    {
        return $this->hasMany(SupplierPaymentDetail::class, 'master_id');
    }

    public function scopeStore($query)
    {
         return $query->where('store_id',Auth::user()->store_id);
    }
    
   public function user(){
     
     return $this->hasMany(User::class,'id','user_id');
  }

  public function supplier()
{
    return $this->belongsTo(Supplier::class, 'payment_to');
}


public function shipment()
{
    return $this->belongsTo(Shipment::class, 'shipment_id'); // Ensure 'shipment_id' exists in SupplierPaymentMaster table
}

}

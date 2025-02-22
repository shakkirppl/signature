<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'supplier_payment_details';
    protected $fillable = ['master_id', 'pi_no','amount','balance_amount','paid','total_amount','total_balance','total_paid','user_id','store_id','conformation_id'];

    public function master()
    {
        return $this->belongsTo(SupplierPaymentMaster::class, 'master_id');
    }

    public function scopeStore($query)
    {
         return $query->where('store_id',Auth::user()->store_id);
    }
    
   public function user(){
     
     return $this->hasMany(User::class,'id','user_id');
  }
}

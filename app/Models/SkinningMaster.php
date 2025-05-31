<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkinningMaster extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'skinning_master';
    protected $fillable = ['id', 'date','time','skinning_code','shipment_id','user_id','store_id','status','delete_status'];
    protected $dates = ['deleted_at']; 
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
   

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }


    public function skinningDetails()
{
    return $this->hasMany(SkinningDetail::class, 'skinning_id', 'id');
}




}

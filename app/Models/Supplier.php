<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory ,SoftDeletes;
    protected $table = 'supplier';
    protected $fillable = ['id', 'name','code','address','contact_number','email','credit_limit_days','store_id','user_id','opening_balance','dr_cr','state','country','account_head_id','delete_status'];


    protected $dates = ['deleted_at']; 
public function purchaseConfirmations()
{
    return $this->hasMany(PurchaseConformation::class, 'supplier_id');
}

public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class, 'supplier_id');
}


public function inspection()
    {
        return $this->hasMany(Inspection::class, 'supplier_id', 'id');
    }

    
public function outstanding()
{
    return $this->hasMany(Outstanding::class, 'account_id', 'id');
}


}

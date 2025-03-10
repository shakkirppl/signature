<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['id', 'name','code','address','contact_number','email','credit_limit_days','store_id','user_id','opening_balance','dr_cr','state','country'];



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

}

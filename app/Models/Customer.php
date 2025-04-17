<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'customer';
    protected $fillable = ['id', 'customer_code','customer_name','email','address','state','country',    'user_id',
    'store_id', 'credit_limit_days','opening_balance', 'dr_cr','account_head_id','contact_number'
    
];
protected $dates = ['deleted_at'];

public function outstanding()
{
    return $this->hasMany(Outstanding::class, 'account_id', 'id');
}

}

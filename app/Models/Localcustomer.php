<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localcustomer extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'local_customer';
    protected $fillable = ['id', 'customer_code','customer_name','email','address','state','country', 'user_id',
    'store_id',
];

protected $dates = ['deleted_at']; 



}

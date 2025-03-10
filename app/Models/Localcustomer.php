<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localcustomer extends Model
{
    use HasFactory;
    protected $table = 'local_customer';
    protected $fillable = ['id', 'customer_code','customer_name','email','address','state','country',    'user_id',
    'store_id',
];



}

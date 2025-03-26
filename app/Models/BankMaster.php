<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankMaster extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'bank_master';

    protected $fillable = [
        'code',
        'bank_name',
        'currency',
        'type',
        'gl',
        'store_id',
        'user_id',
        'account_no',
        'account_name',
        'account_head_id'
    ];
    protected $dates = ['deleted_at']; 

  
}

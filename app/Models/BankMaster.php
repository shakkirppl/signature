<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankMaster extends Model
{
    use HasFactory;

    protected $table = 'bank_master';

    protected $fillable = [
        'code',
        'bank_name',
        'currency',
        'type',
        'gl',
        'store_id',
        'user_id',
    ];

  
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    use HasFactory;
    protected $table = 'opening_balance';
    protected $fillable = [ 
    'account_id',
    'opening_balance',
    'dr_cr',
    'account_type',
    'store_id',
    'user_id',
    ];
}

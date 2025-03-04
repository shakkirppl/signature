<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurruntDollerRate extends Model
{
    use HasFactory;

    protected $table = 'current_dollar_rate';

    protected $fillable = [
        'dollar',
        'shilling'
    ];

  
}

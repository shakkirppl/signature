<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewSlaughterTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'new_slaughter_time';

    protected $fillable = [
        'date',
        'time',
        'user_id',
    ];
}

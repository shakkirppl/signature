<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChillingRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chilling_room';
    protected $fillable = [
        'date',
        'time',
        'initial_carcass_temp',
        'exit_temp_carcass',
        'chiller_temp_humidity',
        'user_id',
        'store_id',
    ];
}

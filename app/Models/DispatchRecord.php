<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispatchRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dispatch_records_forms';

    protected $fillable = [
        'date',
        'no_of_carcasses',
        'customer_name',
        'dispatch_temperature',
        'packaging_material_used',
        'comments',
        'user_id',
        'store_id',
        'production_date',
        'expire_date'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsdToShilling extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'usd_to_shilling';

    protected $fillable = ['usd', 'shilling'];
    protected $dates = ['deleted_at'];
}

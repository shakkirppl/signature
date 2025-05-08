<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakdownReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'breakdown_reports';

    protected $fillable = [
        'user_id',
        'store_id',
        'date',
        'equipment_id',
        'problem_reported',
        'action_taken',
        'time_out_of_service',
    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntemortemGeneralCondition extends Model
{
    use HasFactory;
    protected $table = 'antemortem_general_conditions';

    protected $fillable = [
        'report_id',
        'condition_type',
        'suspect',
        'not_suspect',
       
    ];
}

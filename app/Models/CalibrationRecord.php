<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;

class CalibrationRecord extends Model
{
        use HasFactory, SoftDeletes;

    protected $table = 'calibration_records';
    protected $fillable = [
    'date',
    'equipment_name',
    'standard_used',
    'calibration_result',
    'next_calibration_due',
    'technician_name',
    'signature',
    'user_id',
    'store_id'
];
   
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WaterQualityTestRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'water_quality_test_records';
    protected $fillable = [
    'date',
        'sampling_point',
        'test_parameters',
        'results',
        'standards_met',
        'lab_technician',
        'signature',
     'store_id',
        'user_id'
];
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

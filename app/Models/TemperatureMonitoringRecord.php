<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemperatureMonitoringRecord extends Model
{
        use HasFactory, SoftDeletes;

    protected $table = 'temperature_monitoring_records';
    protected $fillable = [
    'date',
    'time',
    'temp_before',
    'temp_after',
    'slaughter_area',
    'average_carcass',
    'inspector_name',
    'inspector_signature',
    'store_id',
    'user_id'
];
  public function user()
    {
        return $this->belongsTo(User::class);
    }
}

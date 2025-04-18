<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlaughterSchedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'slaughter_schedules_master';
    protected $fillable = ['slaughter_no','date', 'loading_time','airport_time','airline_name','airline_number','airline_date','airline_time','starting_time_of_slaughter','user_id','store_id','ending_time_of_slaughter','transportation_date','transportation_time','slaughter_date','slaughter_end_date','loading_end_time','loading_end_date','loading_start_date',];

    protected $dates = ['deleted_at'];

    public function details()
    {
        return $this->hasMany(SlaughterScheduleDetail::class, 'slaughter_master_id');
    }
}

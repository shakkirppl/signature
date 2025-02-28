<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaughterSchedule extends Model
{
    use HasFactory;

    protected $table = 'slaughter_schedules_master';
    protected $fillable = ['slaughter_no','date', 'loading_time','airport_time','airline_name','airline_number','airline_date','airline_time','starting_time_of_slaughter','user_id','store_id','ending_time_of_slaughter','transportation_date','transportation_time','slaughter_date'];

    public function details()
    {
        return $this->hasMany(SlaughterScheduleDetail::class, 'slaughter_master_id');
    }
}

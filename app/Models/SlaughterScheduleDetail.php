<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlaughterScheduleDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'slaughter_schedules_detail';
    protected $fillable = [ 'slaughter_master_id','product_id', ];
    protected $dates = ['deleted_at'];


    public function master()
    {
        return $this->belongsTo(SlaughterScheduleMaster::class, 'slaughter_master_id');
    }

    public function products()
{
    return $this->belongsTo(Product::class ,'product_id');
}
}

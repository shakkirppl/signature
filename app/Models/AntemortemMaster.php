<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntemortemMaster extends Model
{
    use HasFactory;
    protected $table = 'antemortem_report_master';

    protected $fillable = [
        'antemortem_no',
        'inspection_date',
        'store_id',
        'user_id',
       
    ];

    public function animal()
    {
        return $this->hasMany(AntemortemAnimalInspection::class, 'report_id', 'id');
    }

    public function condition()
    {
        return $this->hasMany(AntemortemGeneralCondition::class, 'report_id', 'id');
    }

    public function sampleType()
    {
        return $this->hasMany(AntemortemSampleSubmission::class, 'report_id', 'id');
    }

    public function comment()
    {
        return $this->hasMany(AntemortemComment::class, 'report_id', 'id');
    }
}

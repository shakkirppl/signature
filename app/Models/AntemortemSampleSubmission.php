<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntemortemSampleSubmission extends Model
{
    use HasFactory;
    protected $table = 'antemortem_sample_submission';

    protected $fillable = [
        'report_id',
        'sample_identification_type',
        'sample_location',
        'hold_tag',
        'date_submitted',
       
    ];
}

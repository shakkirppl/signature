<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostmortemSamples extends Model
{
    use HasFactory;
    protected $table = 'postmortem_sample_details';
    protected $fillable = [ 'postmortem_id', 'sample_identification_type', 'sample_location', 'hold_tag', 'date_submitted','user_id','store_id',];
}

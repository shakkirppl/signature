<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CleaningSanitationRecord extends Model
{
     use HasFactory,SoftDeletes;

    protected $table = 'cleaning_sanitation_records';
    protected $fillable = [
    'date',
    'cleaning_method',
    'cleaning_agent',
    'area_cleaned',
    'cleaner_name',
    'supervisor_check',
    'verification_signature',
    'user_id',
    'store_id',
    'comments'
];

public function user()
{
    return $this->belongsTo(User::class);
}

}

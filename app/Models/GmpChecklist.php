<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GmpChecklist extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'gmp_checklists';
      protected $fillable = [
        'date',
        'facility_cleanliness',
        'pest_control',
        'personal_hygiene',
        'equipment_sanitation',
        'store_id',
        'user_id'
       
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

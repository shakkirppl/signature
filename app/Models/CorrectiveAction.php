<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;


class CorrectiveAction extends Model
{
      use HasFactory, SoftDeletes;
   


 

    protected $table = 'corrective_actions';
    protected $fillable = [
    'date',
    'non_conformity',
    'action_taken',
    'responsible_person',
    'department',
    'root_cause',
    'date_of_completion',
    'verified_by',
    'signature',
     'store_id',
        'user_id'
];
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionHistory extends Model
{
    use HasFactory;
     protected $table = 'action_histories'; 
     protected $fillable = [
        'page_name',
        'record_id',
        'action_type',
        'user_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}

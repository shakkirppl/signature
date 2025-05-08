<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFeedback extends Model
{
    use SoftDeletes;

    protected $table = 'customer_feedback';

    protected $fillable = [
        'date',
        'customer_id',
        'feedback',
        'user_id',
        
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}


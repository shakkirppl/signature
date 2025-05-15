<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerComplaint extends Model
{
   use HasFactory, SoftDeletes;        
   protected $table = 'customer_complaints';

     protected $fillable = [
        'date_received',
        'customer_name',
        'complaint_details',
        'investigation_findings',
        'corrective_action',
        'responsible_person',
        'date_closed',
        'manager_signature',
        'user_id',
        'store_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

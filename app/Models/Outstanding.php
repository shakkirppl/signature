<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outstanding extends Model
{
    use HasFactory;

    protected $table = 'outstanding';
    protected $fillable = ['date', 'time','account_id','receipt','payment','narration','transaction_id','transaction_type','description','account_type','store_id','user_id','financial_year', ];

    public function supplier()
     {
        return $this->belongsTo(Supplier::class, 'account_id');
    }

    public function customer()
     {
        return $this->belongsTo(Customer::class, 'account_id');
    }
    
}

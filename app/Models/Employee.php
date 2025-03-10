<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';
    protected $fillable = ['id', 'employee_code','name','email','contact_number','designation_id', 'user_id','store_id',];


   

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }
}

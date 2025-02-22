<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectMaster extends Model
{
    use HasFactory;
    protected $table = 'reject_masters';
    protected $fillable = ['id', 'rejected_reasons'];
}

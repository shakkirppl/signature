<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMortemMaster extends Model
{
    use HasFactory;
    protected $table = 'postmortem_report_master';
    protected $fillable = [ 'postmortem_no','inspection_date','user_id','store_id',];
}

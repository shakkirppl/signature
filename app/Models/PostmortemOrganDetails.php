<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostmortemOrganDetails extends Model
{
    use HasFactory;
    protected $table = 'postmortem_organs_details';
    protected $fillable = [ 'postmortem_id', 'organ_type', 'organs_approved', 'organs_held', 'organs_condemned','user_id','store_id',];
}

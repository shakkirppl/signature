<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostmortemComments extends Model
{
    use HasFactory;
    protected $table = 'postmortem_comments';
    protected $fillable = [ 'postmortem_id', 'comment','user_id','store_id',];
}

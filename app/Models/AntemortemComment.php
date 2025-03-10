<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntemortemComment extends Model
{
    use HasFactory;
    protected $table = 'antemortem_comments';

    protected $fillable = [
        'report_id',
        'comment_text',
        
       
    ];
}

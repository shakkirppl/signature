<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMortemMaster extends Model
{
    use HasFactory;
    protected $table = 'postmortem_report_master';
    protected $fillable = [ 'postmortem_no','inspection_date','user_id','store_id',];

    public function animals()
    {
        return $this->hasMany(PostmortemAnimalDetails::class, 'postmortem_id', 'id');
    }

    public function organs()
    {
        return $this->hasMany(PostmortemOrganDetails::class, 'postmortem_id', 'id');
    }

    public function samples()
    {
        return $this->hasMany(PostmortemSamples::class, 'postmortem_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(PostmortemComments::class, 'postmortem_id', 'id');
    }
}

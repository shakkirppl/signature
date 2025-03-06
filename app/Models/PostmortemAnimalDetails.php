<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostmortemAnimalDetails extends Model
{
    use HasFactory;
    protected $table = 'postmortem_animal_details';
    protected $fillable = [ 'postmortem_id', 'animal_type', 'carcasses_approved', 'carcasses_held', 'carcasses_condemned','user_id','store_id',];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntemortemAnimalInspection extends Model
{
    use HasFactory;

    protected $table = 'antemortem_animal_inspection';

    protected $fillable = [
        'report_id',
        'animal_type',
        'quantity_pass',
        'quantity_held',
        'quantity_condemned',
        'vet_contacted',
        'manager_contacted',
       
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeathAnimalDetail extends Model
{
    use HasFactory;

    protected $table = 'death_animal_detail';
    protected $fillable = ['death_animal_master_id', 'product_id', 'death_male_qty', 'death_female_qty', 'total_death_qty'];

    public function master()
    {
        return $this->belongsTo(DeathAnimalMaster::class, 'death_animal_master_id');
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeathAnimalMaster extends Model
{
    use HasFactory,SoftDeletes;

      
    protected $table = 'death_animal_master';
    protected $fillable = ['date', 'shipment_id', 'supplier_id', 'inspection_id','store_id','user_id'];

    public function details()
    {
        return $this->hasMany(DeathAnimalDetail::class, 'death_animal_master_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id');
    }

    protected $dates = ['deleted_at']; 
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinningDetail extends Model
{
    use HasFactory;
    

    protected $table = 'skinning_detail';
    protected $fillable = ['id', 'skinning_id','employee_id','product_id','quandity','skin_percentage','store_id'];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function skinningMaster()
{
    return $this->belongsTo(SkinningMaster::class, 'skinning_id', 'id');
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'inspection_detail';
    protected $fillable = ['inspection_id', 'product_id', 'qty','male_accepted_qty',
    'female_accepted_qty','male_rejected_qty','female_rejected_qty',
    'rejected_reason','rate','total','store_id','user_id','status','type','mark','received_qty'
,'death_male_qty','death_female_qty'];

protected $dates = ['deleted_at']; 

    public function inspection()
{
    return $this->belongsTo(Inspection::class, 'inspection_id');
}


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function rejectMaster()
{
    return $this->belongsTo(RejectMaster::class, 'rejected_reason');
}

    
}

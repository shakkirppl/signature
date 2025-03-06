<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionDetail extends Model
{
    use HasFactory;
    protected $table = 'inspection_detail';
    protected $fillable = ['inspection_id', 'product_id', 'qty','male_accepted_qty','female_accepted_qty','male_rejected_qty','female_rejected_qty','rejected_reason','rate','total','store_id','user_id','status','type','mark','received_qty'];

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

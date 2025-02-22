<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionDetail extends Model
{
    use HasFactory;
    protected $table = 'inspection_detail';
    protected $fillable = ['inspection_id', 'product_id', 'qty','accepted_qty','rejected_qty','rejected_reason','rate','total','store_id','user_id','status','type','mark'];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
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

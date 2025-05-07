<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionRecord extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'production_record_form';
    protected $fillable = [
        'date',
        'product_id',
        'processing_line',
        'number_of_animals',
        'user_id',
        'store_id'
    ];
    public function product()
{
    return $this->belongsTo(Product::class);
}

    
    // 
}

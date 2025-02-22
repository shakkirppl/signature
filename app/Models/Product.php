<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $fillable = ['id', 'product_name','category_id','hsn_code','product_image','description','user_id',
    'store_id',];

    public function category()
    {
        return $this->belongsTo(Category::class); 
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RequestingFormDetail extends Model
{
    use HasFactory;

    protected $table = 'request_form_details';
    protected $fillable = [
        'requesting_form_id',
        'product_id',
        'qty',
        'male',
        'female',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function requestingForm()
    {
        return $this->belongsTo(RequestingForm::class);
    }
}

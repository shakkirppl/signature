<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Airline extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'airline_payment'; 

    protected $fillable = [
        'code',
        'date',
        'airline_name',
        'airline_number',
        'shipment_id',
        'customer_id',
        'coa_id',
        'air_waybill_no',
        'air_waybill_charge',
        'documents_charge',
        'amount',
        'description',
        'total_weight',
        'store_id',
        'user_id',
        'type',
        'currency'
    ];

    protected $dates = ['deleted_at']; 
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

   
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

   
    public function coa()
    {
        return $this->belongsTo(COA::class, 'coa_id');
    }
  public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

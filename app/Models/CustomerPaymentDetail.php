<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentDetail extends Model
{
    use HasFactory;

    protected $table = 'customer_payment_details';
    protected $fillable = ['master_id', 'pi_no','amount','balance_amount','paid','total_amount','total_balance','total_paid','user_id','store_id','sales_payment_id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseExpense extends Model
{
    use HasFactory;
    protected $table = 'purchase_expenses';
    protected $fillable = [ 
    'purchase_id',
    'expense_id',
    'amount',
    'store_id',
    ];

}

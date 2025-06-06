<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class Outstanding extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'outstanding';
    protected $fillable = ['date', 'time','account_id','receipt','payment','narration','transaction_id','transaction_type','description','account_type','store_id','user_id','financial_year', ];
    protected $dates = ['deleted_at'];
    public function supplier()
     {
        return $this->belongsTo(Supplier::class, 'account_id');
    }

    public function customer()
     {
        return $this->belongsTo(Customer::class, 'account_id');
    }


     public function scopeAgingSummary($query, $accountType)
    {
        return $query->select([
                'account_id',
                'account_type',
                DB::raw('SUM(payment) as total_payment'),
                DB::raw('SUM(receipt) as total_receipt'),
                DB::raw('MAX(date) as last_transaction_date'),
                DB::raw('DATEDIFF(CURDATE(), MAX(date)) as days_outstanding')
            ])
            ->where('account_type', $accountType)
            ->groupBy('account_id', 'account_type');
    }


    public function getDaysSinceLastTransactionAttribute()
{
    $latestDate = $this->created_at ?? $this->date; // fallback to created_at
    return Carbon::parse($latestDate)->diffInDays(Carbon::now());
}
    
}

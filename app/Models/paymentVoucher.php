<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class paymentVoucher extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'payment_voucher';
    protected $fillable = ['code', 'date','name','coa_id','type','amount','description','bank_id','store_id','user_id','employee_id','currency','delete_status','edit_status','edit_request_data'];

   


    public function bank()
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(AccountHead::class, 'coa_id');
    }   
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); // Ensure 'employee_id' is used as the foreign key
    }
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

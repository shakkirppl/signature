<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentVoucher extends Model
{
    use HasFactory;

    protected $table = 'payment_voucher';
    protected $fillable = ['code', 'date','name','coa_id','type','amount','description','bank_id','store_id','user_id','employee_id'];

    public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('payment_voucher', Auth::user()->store_id = 1);
    
            // Update the invoice number in the database
            InvoiceNumber::updateinvoiceNumber('payment_voucher', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }


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
}

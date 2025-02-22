<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptVoucher extends Model
{
    use HasFactory;
    protected $table = 'receipt_voucher';
    protected $fillable = ['code', 'date','name','customer_id','type','amount','description','bank_id','store_id','user_id'];

    public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('receipt_voucher', Auth::user()->store_id = 1);
    
            // Update the invoice number in the database
            InvoiceNumber::updateinvoiceNumber('receipt_voucher', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }


    public function bank()
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'id');
    }

    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id', 'id');
}

public function account()
    {
        return $this->belongsTo(AccountHead::class, 'coa_id');
    }  

}

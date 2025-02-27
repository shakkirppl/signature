<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['id', 'name','code','address','contact_number','email','credit_limit_days','store_id','user_id','opening_balance','dr_cr','state','country'];


    public function invoice_no()
{
    try {
        $invoice_no = InvoiceNumber::ReturnInvoice('supplier_code', Auth::user()->store_id = 1);

        // Update the invoice number in the database
        InvoiceNumber::updateinvoiceNumber('supplier_code', Auth::user()->store_id = 1);
        
        return $invoice_no;
    } catch (\Exception $e) {
        return $e->getMessage();
    }
  
}

public function purchaseConfirmations()
{
    return $this->hasMany(PurchaseConformation::class, 'supplier_id');
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localcustomer extends Model
{
    use HasFactory;
    protected $table = 'local_customer';
    protected $fillable = ['id', 'customer_code','customer_name','email','address','state','country',    'user_id',
    'store_id',
];

public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('local_customer', Auth::user()->store_id = 1);
    
            // Update the invoice number in the database
            InvoiceNumber::updateinvoiceNumber('local_customer', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }

}

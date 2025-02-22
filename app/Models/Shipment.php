<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table = 'shipment';
    protected $fillable = ['date', 'time', 'shipment_no','shipment_status'];



    public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('shipment', Auth::user()->store_id = 1);
    
            InvoiceNumber::updateinvoiceNumber('shipment', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
}

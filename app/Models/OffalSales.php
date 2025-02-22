<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffalSales extends Model
{
    use HasFactory;
    protected $table = 'offal_sales_master';
    protected $fillable = [ 
    'order_no',
    'date',
    'shipment_id',
    'lo_customer_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'status',
    'store_id',
    'user_id',];

    
    public function localcustomer()
    {
        return $this->belongsTo(Localcustomer::class, 'lo_customer_id');
    }

    public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('offal-sales', Auth::user()->store_id = 1);
    
            // Update the invoice number in the database
            InvoiceNumber::updateinvoiceNumber('offal-sales', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }

    public function details()
    {
        return $this->hasMany(OffalSalesDetail::class, 'offal_sales_id');
    }
      
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

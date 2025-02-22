<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightCalculatorMaster extends Model
{
    use HasFactory;
    
    protected $table = 'weight_calculator_master';
    protected $fillable = ['id', 'date','weight_code','shipment_id','total_weight','user_id','store_id','status','supplier_id'];

    public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('weight_calculator', Auth::user()->store_id = 1);
    
            // Update the invoice number in the database
            InvoiceNumber::updateinvoiceNumber('weight_calculator', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';
    protected $fillable = [ 
    'order_no',
    'date',
    'supplier_id',
    'grand_total',
    'advance_amount',
    'balance_amount',
    'status',
    'inspection_status',
    'store_id',
    'user_id',
    'shipment_id'
];


    public function invoice_no()
    {
        try {
            $invoice_no = InvoiceNumber::ReturnInvoice('purchase_order', Auth::user()->store_id = 1);
    
            // Update the invoice number in the database
            InvoiceNumber::updateinvoiceNumber('purchase_order', Auth::user()->store_id = 1);
            
            return $invoice_no;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
      
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
   

    public function Detail()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }


    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }
    

    // Automatically delete associated details when a SalesOrder is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($salesOrder) {
            // Delete all related SalesOrderDetails
            $salesOrder->details()->delete();
        });
    }

    public function products()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }

    public function shipments()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
    
    
}

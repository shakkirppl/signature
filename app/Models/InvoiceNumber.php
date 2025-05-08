<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceNumber extends Model
{
    use HasFactory;
    protected $table = 'invoice_numbers';
    protected $fillable = ['id', 'bill_type','bill_mode','bill_no','bill_prefix','store_id','financial_year'];
    use HasFactory;
    public static function updateinvoiceNumber($bill_mode,$store_id)
    {
        $invoice_no =  self::where('bill_mode',$bill_mode)->where('store_id',$store_id)->first();
        $invoice_no->bill_no = $invoice_no->bill_no+1;
        $invoice_no->save();
     
    }
    public static function ReturnInvoice($bill_mode, $store_id)
    {
        $invoice_no = self::where('bill_mode', $bill_mode)->where('store_id', $store_id)->first();
        
        // Increment the bill number
        
        
        $bill_no = $invoice_no->bill_no;
        $code = '';
        
        if ($bill_no < 10) {
            $code = '000';
        } elseif ($bill_no < 100) {
            $code = '00';
        } elseif ($bill_no < 1000) {
            $code = '0';
        } else {
            $code = '';
        }
        
        return $invoice_no->bill_prefix . $code . $bill_no;
    }
    
    public static function decreaseInvoice($bill_mode, $store_id)
{
    $invoice = self::where('bill_mode', $bill_mode)
        ->where('store_id', $store_id)
        ->first();

    if ($invoice && $invoice->bill_no > 1) {
        $invoice->bill_no -= 1;
        $invoice->save();
    }
}
    
}

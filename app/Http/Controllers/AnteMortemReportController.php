<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;

class AnteMortemReportController extends Controller
{
    public function create()
    {
        return view('antemortem-report.create',[ 'invoice_no' => $this->invoice_no()]);
    }

    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('antemortem_no',Auth::user()->store_id=1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
        }

}

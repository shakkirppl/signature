<?php

namespace App\Http\Controllers;

use Request;

use App\Models\AccountTransactions;
use App\Http\Requests;
use App\Models\AccountHead;


class LedgerController extends Controller
{
   
    public function index()
    {
        //
        try {
             $in_date = Request::get('from_date') ? Request::get('from_date') : date('Y-m-d');
             $out_date = Request::get('to_date') ? Request::get('to_date'): date('Y-m-d'); 
          $ledgers = AccountTransactions::join('account_heads','account_heads.id','=','account_transactions.client_id1')
                            ->select('account_transactions.*','account_heads.name')
                            ->where('account_transactions.client_id','=',Request::get('account'))
                            ->whereBetween('account_transactions.date',[$in_date,$out_date])
                            ->orderBy('date')
                            ->get();
$dr = AccountTransactions::join('account_heads','account_heads.id','=','account_transactions.client_id1')
                             ->select('account_transactions.*','account_heads.name')
                             ->where('account_transactions.client_id','=',Request::get('account'))
                             ->where('account_transactions.date','<',$in_date)
                             ->sum('dr');
              $cr = AccountTransactions::join('account_heads','account_heads.id','=','account_transactions.client_id1')
                             ->select('account_transactions.*','account_heads.name')
                             ->where('account_transactions.client_id','=',Request::get('account'))
                             ->where('account_transactions.date','<',$in_date)
                             ->sum('cr');
              $opening_balance =  $dr - $cr;
              $total_dr =  $ledgers->sum('dr') ;
              $total_cr = $ledgers->sum('cr');
               $accounts = AccountHead::pluck('name','id')->toArray();
      
              $closing_balance = $opening_balance + ($total_dr - $total_cr); 
     return view('ledger.index',['ledgers'=>$ledgers,'accounts'=>$accounts,'opening_balance'=>$opening_balance,'total_cr'=>$total_cr,'total_dr'=>$total_dr,'closing_balance'=>$closing_balance]);

      } catch (\Exception $e) {
     
    return $e->getMessage();
  }
    }

   
   
}

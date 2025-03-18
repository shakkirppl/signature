<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Arabian Fresh Invoice</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- bootstrap & fontawesome -->
<!--        <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{{URL::to('/')}}/assets/font-awesome/4.5.0/css/font-awesome.min.css" />-->
<style type="text/css">
    body{
        font-family:Verdana;
        font-size:12px;
    }
    @media print{
        #buttons{
            display:none;
        }
        
        
        /*#myDiv {page-break-after: always;}*/
        
    }

   body, html {

        /*background:#fff;*/
        height:100%;
        /*padding:0;
        margin:0*/
        
        
        }
    
    
    
</style>
    </head>
    <body>
        <div style="width:21cm" id="myDiv">
            <table border="0" width="100%">
            <tr>
                <td colspan="2"><h3 style="margin:0" align="center">ARABIAN FRESH Online</h3></td>
                </tr>
                <tr>
                <td  colspan="2" style="line-height: 15px"  align="center">Al Jazeera Street, Bin Mohamood<br>P.O.Box 16489, Tel : 44887756<br>Mob : 55684646</td>
                
                
            </tr>
            </table>
            
            <table border="1" width="100%">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Account</th>
                    </tr>
                    <tr>
                        <td>{{date('d/m/Y',strtotime($in_date))}}</td>
                        <td> {{date('d/m/Y',strtotime($out_date))}} </td>
                        <td>{{$account}}</td>
                    </tr>
                </thead>
            </table>    
            <br>
            <table border="1" width="100%" >
                    <thead>
                        <tr> 
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 25%;">Accounts</th>
                            <th style="width: 12.5%;">Dr</th>
                            <th style="width: 12.5%;">Cr</th>
                            <th style="width: 20%;">Description</th>
                            <th style="width: 10%;">Narration</th>
                             
                            
                        </tr>
                        
                    </thead>

                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Opening Balance</td>
                            
                            <td @if($opening_balance > 0)>{{number_format($opening_balance,2,'.',',')}}</td @endif>
                            
                          <td @if($opening_balance < 0)>{{number_format(abs($opening_balance),2,'.',',')}}</td @endif>
                          
                          <td></td>
                          <td></td>
                        </tr>
                        
                        @if(count($ledgers))
                        @foreach($ledgers as $key=>$ledger)
                        @if($ledger->dr != 0.00 || $ledger->cr != 0.00 )
                        <tr id="{{$ledger->id}}">
                            <td style="width: 5%;">{{$key + 1}}</td>
                            <td style="width: 15%;" class="date">{{date('d/m/Y',strtotime($ledger->date))}}</td>
                            <td style="width: 25%;" class="name">{{$ledger->name}}</td>
                            <td style="width: 12.5%;" class="dr">{{$ledger->dr ? number_format($ledger->dr,2,'.',',') : null}}</td>
                            <td style="width: 12.5%;" class="cr">{{$ledger->cr ? number_format($ledger->cr,2,'.',',') : null}}</td>
                            <td style="width: 20%;" class="description">{{$ledger->description}}</td>
                            <td style="width: 10%;" class="narration">{{$ledger->narration}}</td>
                            
                          </tr>
                          @endif
                        @endforeach
                        
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Total</td>
                          <td>{{number_format($total_dr,2,'.',',')}}</td> 
                          <td>{{number_format($total_cr,2,'.',',')}}</td>
                          <td></td>
                          <td></td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Closing Balance</td>
                          <td @if($closing_balance > 0)>{{number_format($closing_balance,2,'.',',')}}</td @endif> 
                          <td @if($closing_balance < 0)>{{number_format(abs($closing_balance),2,'.',',')}}</td @endif>
                          <td></td>
                          <td></td>
                        </tr>
                        
                        @else
                       
                        @endif
                    </tbody>
                </table>
        </div>
        <br><br>
        <div id="buttons">
            <button onclick="window.print(); return false">Print</button>
            @if(Input::get('destination'))
            <button onclick="window.location='{{URL::to(Input::get('destination'))}}'; return false">Close</button>
            @else
            <button onclick="window.close(); return false">Close</button>
            @endif
        </div>
        
    </body>
</html>

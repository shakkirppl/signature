<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commercial Invoice</title>
    <style>
   
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    font-size: 11px;
}

.a4-container {
    width: 210mm;
    /* height: 295mm; */
    padding: 0.5mm;
    margin: 0 auto;
    box-sizing: border-box;
    background-color: white;
    overflow: hidden;
    background-image: url("{{ asset('image/Back.jpg') }}");
    background-size: cover;
    background-position: center top;
    background-repeat: no-repeat;
    position: relative;
    page-break-after: always;
}

.packing-list {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    border: 1px solid black;
}

.packing-list th, .packing-list td {
    border: 1px solid #333;
    padding: 5px;
    text-align: left;
    height: 18px;
    vertical-align: middle;
}

.header {
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    padding: 6px;
}

.company-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px;
    font-size: 14px;
}

.logo {
    height: 90px; /* or any height you want */
    max-width: 160px;
    display: block;
    margin: 5px auto 0 auto;
    position: relative;
    z-index: 2;
    object-fit: contain; /* Optional: keeps image from stretching */
}


.image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 350px;
    margin: 10px auto;
    gap: 70px;
}

.image-container img {
    width: 50px;
    height: 50px;
}

.total-row td {
    font-weight: bold;
    text-align: center;
}

.packing-list th {
    border-top: 1px solid black !important;
    border-bottom: 1px solid black !important; /* BLACK bottom border */
    border-left: 1px solid black !important;
    border-right: 1px solid black !important;
  
    text-align: left;
  
    vertical-align: middle;
}

.null-td {
    border-top: 1px solid #ddd !important;
    border-bottom: 1px solid #ddd !important;
    border-left: 1px solid black;
    border-right: 1px solid black;
   
    text-align: left;
 
    vertical-align: middle;
   
}

.no-border-row td {
 border-top: 1px solid #ddd !important;
    border-bottom: 1px solid #ddd !important;
    border-left: 1px solid black;
    border-right: 1px solid black;
 
    text-align: left;
  
    vertical-align: middle;

}



@media print {
    body, .a4-container {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background-image: url("{{ asset('image/Back.jpg') }}") !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        background-position: top center !important;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        height: 100% !important;
    }

   



    button {
        display: none !important;
    }
}

@page {
    size: A4;
    margin: 3mm; /* Was previously 2mm, keep or decrease to 0 if needed */
}

    </style>

</head>
<body>
    <div class="a4-container">
        <!-- <div class="row"> -->
    

        <div class="company-header">
    <div>
        <img class="logo" src="{{ url('image/signature-logo.png') }}" alt="Company Logo">
    </div>

    <div class="company-info">
        <strong>Signature Trading Ltd</strong><br>
        P.O Box 1506, Plot No. 147<br>
        Fire Road, Arusha<br>
        <strong>   United Republic Of Tanzania</strong><br>
    </div>

    <div class="company-info-right">
        <strong>Registration No:</strong> 157893564 <br>
        <strong>Tel:</strong> +255 272 97 97 97 <br>
        <strong>Tel:</strong> +255 69 666 6606 <br>
        <a href="https://www.signaturetz.com">www.signaturetz.com</a> <br>
        <strong>Email:</strong> <a href="sales@signaturetz.com">sales@signaturetz.com</a>
    </div>
</div>

       
        <table class="packing-list">
            <tr>
                <td class="header" colspan="2">Commercial Invoice</td>
                <td colspan="4">
                    <div class="invoice-container">
                        <div class="row-invoice">
                            <strong>Invoice:</strong> <span class="highlight" style="margin-left: 54px; color:red">{{$order->order_no }}</span> 
                            <span   style="margin-left: 50px;" >Invoice Date: {{ date('d/m/Y', strtotime($order->date)) }}</span> 
                        </div> 
                        <br>
                        <div class="row">
                            <strong>Customer Id:</strong> <span   style="margin-left: 20px;" > {{ $order->customer_code }}</span>
                        </div>
                        <br>
                        <div class="row">
                            <strong>Page:</strong> <span style="margin-left: 70px;">  1.00</span>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong>SIGNATURE TRADING LIMITED</strong><br>
                    P.O Box 1506, Plot no.147<br>
                    Fire Road, Arusha<br>
                    Tel. +255 272 97 97 97 / 69 666 6606<br>
                    Email: <strong>sales@signaturetz.com</strong>
                </td>
                <td colspan="4">
                    <strong>TAYBAT ALBAHR</strong><br>
                    UMM AL DOOM STREET, MUAITHER<br>
                    P.O BOX: 96393, DOHA, QATAR<br>
                    Tel. +974 30691279
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong><i>Shipping Mode:</i></strong> {{ $order->shipping_mode }}
                    <br><br>
                    <strong><i>Shipping Agent:</i></strong> {{ $order->shipping_agent }}
                </td>
                <td colspan="4">
                    <strong><i>Terms of Delivery:</i></strong> <span class="small-text" style="margin-left: 69px;">C&F ,DOHA, QATAR</span>
                    <br> <br>
                    <strong><i>Terms of Payment:</i></strong> <span class="small-text" style="margin-left: 66px;">100% after receiving</span>
                    <br><br>
                    <strong><i>Currency:</i></strong> <span style="margin-left: 130px;" class="small-text">USD</span><br>
                </td>
            </tr>

            <tr >
                <th class="desc-col" >Description</th>
                <th class="pack-col">Packaging</th>
                <th class="weight-col">Quantity(kg)</th>
                <th class="price-col">Price (USD)</th>
                <th class="par-col">Par</th>
                <th class="total-col">Total</th>
                
            </tr>
            @foreach ($products as $product)
            <tr class="no-border-row">
                <td>{{ $product->description }} <br>{{ $product->hsn_code }}</td>
                <td class="packaging">0.00</td>
                <td class="quantity">{{ number_format($product->quantity, 2) }}</td>
                <td class="price">{{ number_format($product->price, 2) }}</td>
                <td>pcs</td>
                <td class="total_amount">{{ number_format($product->quantity * $product->price, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td  class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <tr>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <tr>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
          
            <tr>
           <td class="bold null-td">  <strong>Country of Origin: TANZANIA </strong></td>
           <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <tr>
            <td class="null-td"><strong>Country of Export: TANZANIA</strong></td>
            <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <td class="null-td"></td>
            <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <td class="null-td"></td>
            <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
         

            <tr>
                <td class="bold" ><strong>Total</strong></td>
                <td><strong>{{ number_format($totalPackaging, 2) }}</strong></td>
                <td><strong>{{ number_format($total_kg, 2) }}</strong></td>
                <td class="total_price"><strong>{{ number_format($totalPrice, 2) }}</strong></td>
                <td></td>
                <td><strong>{{ number_format($totalAmount, 2) }}</td>
            </tr>
            @php
    $shrinkagePercent = floatval($order->shrinkage);
    $shrinkageAmount = ($shrinkagePercent > 0) ? ($totalAmount * $shrinkagePercent / 100) : 0;
    $adjustedTotalAmount = $totalAmount - $shrinkageAmount;
@endphp

<tr>
    <td colspan="5"><strong>Shrinkage : {{ $order->shrinkage }} %</strong></td>
    <td ><strong>{{ number_format($shrinkageAmount, 2) }}</strong></td>
</tr>

<tr class="subtotal">
    <td><strong>Subtotal (After Shrinkage)</strong></td>
    <td><strong>{{ number_format($totalPackaging, 2) }}</strong></td>
    <td><strong>{{ number_format($total_kg, 2) }}</strong></td>
    <td colspan="2" class="total_price"><strong>{{ number_format($totalPrice, 2) }}</strong></td>
   
    <td><strong>{{ number_format($adjustedTotalAmount, 2) }}</strong></td>
</tr>



            <tr class="">
                <td colspan="5" class="" style="text-align: left;">
                <div class="row">
                <h3>Bank Details:</h3>
            <p>
                <strong>USD Account Code: 42710052781 (USD) </strong> <br>
                Swift Code:<strong> NMIBTZTZ </strong> Branch Code: 016 <br>
                Bank Account Owner: Signature Trading Limited <br>
                Bank Name:<strong> NMB BANK PLC </strong><br>
                <strong>Bank Address:</strong> NMB Bank - Arusha Market Branch <br>
                P.O BOX :11168,Arusha,Tanzania
                        </div>
                </td>
                
        
           
            </tr>
           
    
            <tr class="total-row">
    <td class="yellow-section bold">TOTAL KG</td>
    <td>{{ number_format($total_kg, 2) }}</td>
    <td  colspan="3"style=" text-align: right; border: 1px solid #000;">TOTAL USD</td>
    <td style="text-align: center; border: 1px solid #000; font-weight: bold;">{{ number_format($adjustedTotalAmount, 2) }}</td>
</tr>

<tr>
    <td colspan="6">
        <div style="text-align: center; font-size: 14px; padding: 10px;  color: green;">
            <i>YOUR TRUSTED PARTNER IN MEAT EXPORTS,<strong> SIGNATURE TRADING LTD</strong>: QATAR, TANZANIA AND ETHIOPIA.</i>
        </div>
    </td>
</tr>


      
        
        </table>



<div class="image-container" style="display: flex; justify-content: space-between; align-items: center;">
<img src="{{ url('image/stamp1.png') }}" alt="Company Stamp" class="stamp" style="width: 250px;height: 160px;margin-left: -240px; margin-top: -20px;">
    <img src="{{ url('image/dots.png') }}" alt="Dots Icon" style="width: 80px;height: 50px;">
    <img src="{{url('image/QR.png') }}" alt="QR Scanner" style="width: 110px;height: 100px;margin-right: -150px;">
</div>

<button onclick="window.print()" style="display: block; margin: 10px auto; padding: 5px 10px; font-size: 14px; cursor: pointer;">print</button>


    </div>
    

</body>
</html>

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
    font-size: 11px; /* Reduce overall font size */
}

.a4-container {
    width: 210mm;
    height: 297mm;
    padding: 8px; /* Reduce padding */
    margin: 0 auto;
    box-sizing: border-box;
    background-color: white;
    overflow: hidden;
    background-image: url("{{ asset('public/image/back.jpg') }}");
    background-size: cover; 
    background-position: center;
    background-repeat: no-repeat;
}

.packing-list {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px; /* Slightly reduced */
    border: 1px solid black;
}

.packing-list th, .packing-list td {
    border: 1px solid #333; /* Dark border for normal rows */
    padding: 5px;
    text-align: left;
    height: 18px;
    vertical-align: middle;
}

/* Light shadow border for .null-td rows */
.null-td {
   
}


.header {
    font-size: 18px; /* Reduce size */
    font-weight: bold;
    text-align: center;
    padding: 6px;
}

.company-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px;
    font-size: 11px; /* Reduced font size */
}

.logo {
    max-width: 100px; /* Reduce logo size */
}

.image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 350px; /* Reduce width */
    margin: 10px auto;
    gap: 100px; /* Reduce spacing */
}

.image-container img {
    width: 50px; /* Reduce image sizes */
    height: 50px;
}

/* Ensure total row is visible and clear */
.total-row td {
    font-weight: bold;
    text-align: center;
}

/* Prevent content from overflowing and ensure single-page print */
@media print {
    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .a4-container {
        transform: scale(0.85); /* Scale down slightly */
        transform-origin: top;
        page-break-inside: avoid; /* Avoid breaking tables across pages */
    }

    button {
        display: none !important; /* Hide print button */
    }
}

@page {
    size: A4;
    margin: 2mm; /* Reduce margins for more space */
}


    </style>

</head>
<body>
    <div class="a4-container">
        <!-- <div class="row"> -->
    

        <div class="company-header">
    <div>
        <img class="logo" src="{{ asset('public/image/signature-logo.png') }}" alt="Company Logo">
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
        <strong>Email:</strong> <a href="mailto:info@signaturetz.com">info@signaturetz.com</a>
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
                    P.O Box 1506, Plot no.22, Block 7, Condo building<br>
                    Arusha, Tanzania<br>
                    Tel. +255 272 97 97 97 / 69 666 6606<br>
                    Email: <strong>sales@signaturetz.com</strong>
                </td>
                <td colspan="4">
                    <strong>TAYBAT ALBAHR</strong><br>
                    UMM AL DOOM STREET, MUAITHER<br>
                    P.O BOX: 96393, DOHA, QATAR<br>
                    Tel. +974 31075459
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

            <tr>
                <th class="desc-col">Description</th>
                <th class="pack-col">Packaging</th>
                <th class="weight-col">Quantity(kg)</th>
                <th class="price-col">Price (USD)</th>
                <th class="par-col">Par</th>
                <th class="total-col">Total</th>
            </tr>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->description }} <br>{{ $product->hsn_code }}</td>
                <td class="packaging">0.00</td>
                <td class="quantity">{{ number_format($product->quantity, 2) }}</td>
                <td class="price">{{ number_format($product->price, 2) }}</td>
                <td>pcs</td>
                <td class="total_amount">{{ number_format($product->quantity * $product->price, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <tr>
                <td></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <tr>
                <td></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
          
            <tr>
           <td class="bold">  <strong>Country of Origin: TANZANIA </strong></td>
           <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
                <td class="null-td"></td>
            </tr>
            <tr>
            <td><strong>Country of Export: TANZANIA</strong></td>
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
            <tr>
                <td colspan="6"><strong>Shrinkage : {{ $order->shrinkage }} %</strong></td>
            </tr>
            <tr class="subtotal">
                <td><strong>Subtotal </strong></td>
                <td><strong>{{ number_format($totalPackaging, 2) }}</strong></td>
                <td><strong>{{ number_format($total_kg, 2) }}</strong></td>
                <td class="total_price"><strong>{{ number_format($totalPrice, 2) }}</strong></td>
                <td></td>
                <td><strong>{{ number_format($totalAmount, 2) }}</td>
            </tr>

            <tr class="">
                <td colspan="5" class="" style="text-align: left;">
                <div class="row">
                <h3>Bank Details:</h3>
            <p>
                <strong>USD Account Code:</strong> 42710052781 (USD) <br>
                <strong>Swift Code:</strong> NMIBTZTZ Branch Code: 016 <br>
                <strong>Bank Account Owner:</strong> Signature Trading Limited <br>
                <strong>Bank Name:</strong> NMB BANK PLC <br>
                <strong>Bank Address:</strong> NMB Bank - Arusha Market Branch <br>
                P.O BOX
                        </div>
                </td>
                
        
           
            </tr>
           
    
            <tr class="total-row">
    <td class="yellow-section bold">TOTAL KG</td>
    <td>{{ number_format($total_kg, 2) }}</td>
    <td  colspan="3"style=" text-align: right; border: 1px solid #000;">TOTAL USD</td>
    <td style="text-align: center; border: 1px solid #000; font-weight: bold;">{{ number_format($totalAmount, 0) }}</td>
</tr>

<tr>
    <td colspan="6">
        <div style="text-align: center; font-size: 14px; padding: 10px;">
            <i>The deducted Withholding tax amounts remain payable to Signature Trading Limited until TRA Withholding Tax Certificate 
            is presented as proof of payment.</i>
        </div>
    </td>
</tr>


      
        
        </table>



<div class="image-container">
<img src="{{ asset('public/image/stamp.png') }}" alt="Company Stamp" class="stamp" style="width: 400px;height: 100px;">
    <img src="{{ asset('public/image/dots.png') }}" alt="Dots Icon" style="width: 80px;height: 50px;">
    <img src="{{ asset('public/image/scanner.png') }}" alt="QR Scanner">
</div>

<button onclick="window.print()" style="display: block; margin: 10px auto; padding: 5px 10px; font-size: 14px; cursor: pointer;">print</button>


    </div>
    

</body>
</html>

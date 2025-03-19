<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
        }

        .a4-container {
    width: 220mm; /* Increased width */
    height: 297mm;
    margin: auto;
    padding: 20px;  /* Increased padding for better spacing */
    box-sizing: border-box;
    border: 1px solid black;
    background-color: white;
}

.packing-list {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid black;
    margin: 10px auto;
}

        .packing-list th, .packing-list td {
            border: 1px solid black;
            padding: 10px; /* Adjusted padding */
            text-align: left;
        }

        .header {
           
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
          
        }

        .invoice-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .row-invoice {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .row strong {
            min-width: 120px;
        }

        .desc-col { width: 40%; }
        .pack-col { width: 15%; }
        .weight-col { width: 15%; }
        .par-col { width: 10%; }
        .total-col { width: 20%; }

        .highlight { font-weight: bold; color: red; }
        .italic { font-style: italic; }
        .bold { font-weight: bold; }
        .small-text {
    font-size: 14px; 
        }

      

        /* Total section row */
        .total-row td {
            font-weight: bold;
            text-align: center;
        }
        .company-header {
    display: flex;
    justify-content: space-between; /* Ensures space between logo and text */
    align-items: center; /* Aligns items vertically */
    margin-bottom: 2px;
    padding: 10px;
}

.company-header > div:first-child {
    flex-shrink: 0; /* Prevents the logo from shrinking */
}

.company-info, .company-info-right {
    font-size: 14px;
    line-height: 1.5;
    text-align: left;
}

.company-info-right {
    text-align: right;
}

.logo {
    max-width: 150px; /* Adjust as needed */
    height: auto;
    margin-left: 10px;
}
.image-container {
    display: flex;
    align-items: center;
    justify-content: center; /* Center images */
    width: 100%;
    max-width: 400px;
    margin: 20px auto;
    gap: 200px; /* Adds space between images */
    position: relative;
}

.image-container img {
    width: 80px;  
    height: 80px; 
    position: relative;
    z-index: 10;
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
        United Republic Of Tanzania<br>
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
                <td class="header" colspan="2">PACKING LIST</td>
                <td colspan="3">
                    <div class="invoice-container">
                        <div class="row-invoice">
                            <strong>Invoice:</strong> <span class="highlight" >{{$packingList->packing_no }}</span> 
                            Invoice Date: {{ date('d/m/Y', strtotime($packingList->date)) }}
                        </div> 
                        <br>
                        <div class="row">
                            <strong>Customer Id:</strong> {{ $packingList->customer_code }}
                        </div>
                        <br>
                        <div class="row">
                            <strong>Page:</strong> <span style="margin-left: 60px;">  1.00</span>
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
                <td colspan="3">
                    <strong>TAYBAT ALBAHR</strong><br>
                    UMM AL DOOM STREET, MUAITHER<br>
                    P.O BOX: 96393, DOHA, QATAR<br>
                    Tel. +974 31075459
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong><i>Shipping Mode:</i></strong> {{ $packingList->shipping_mode }}
                    <br><br>
                    <strong><i>Shipping Agent:</i></strong> {{ $packingList->shipping_agent }}
                </td>
                <td colspan="3">
                    <strong><i>Terms of Delivery:</i></strong> <span class="small-text">{{ $packingList->terms_of_delivery }}</span>
                    <br> <br>
                    <strong><i>Terms of Payment:</i></strong> <span class="small-text">{{ $packingList->terms_of_payment }}</span>
                    <br><br>
                    <strong><i>Currency:</i></strong> <span class="small-text">USD</span><br>
                </td>
            </tr>

            <tr>
                <th class="desc-col">Description</th>
                <th class="pack-col">Packaging</th>
                <th class="weight-col">Weight</th>
                <th class="par-col">Par</th>
                <th class="total-col">Total</th>
            </tr>

            @foreach($packingList->details as $detail)
    <tr>
        <td>{{ $detail->product->product_name }}<br><i>HS-Code: {{ $detail->product->hsn_code }}</i></td>
        <td class="packaging">{{ $detail->packaging }}</td>
        <td class="quantity">{{ $detail->weight }}</td>
        <td>{{ $detail->par }}</td>
        <td class="total_amount">{{ $detail->total }}</td>
    </tr>
    @endforeach

            <tr>
                <td class="bold">Country of Origin: TANZANIA<br>Country of Export: TANZANIA</td>
                <td colspan="4"></td>
            </tr>

            <tr>
                <td class="bold">Total</td>
                <td>{{ number_format($totalPackaging, 2) }}</td>
                <td>{{ number_format($totalWeight, 2) }}</td>
                <td></td>
                <td>{{ number_format($totalAmount, 2) }}</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr class="subtotal">
                <td>Subtotal</td>
                <td>{{ number_format($totalPackaging, 2) }}</td>
                <td>{{ number_format($totalWeight, 2) }}</td>
                <td></td>
                <td>{{ number_format($totalAmount, 2) }}</td>
            </tr>

            <tr class="">
                <td colspan="4" class="" style="text-align: right;">
                <div class="row">
                            Net Weight:<span style="margin-left: 15px;"> {{ number_format($packingList->net_weight, 2) }}</span>
                        </div>
                        <br>
                        <div class="row">
                            Gross Weight:<span style="margin-left: 15px;"> {{ number_format($packingList->gross_weight, 2) }}</span>
                        </div>
                </td>
                
        
           
            </tr>
           
    
            <tr class="total-row">
    <td class="yellow-section bold">TOTAL KG</td>
    <td>{{ number_format($packingList->net_weight, 2) }}</td>
    <td class="bold">TOTAL Pcs</td>
    <td>{{ number_format($totalPackaging, 2) }}</td>
    <td class="green-section bold" colspan="2">Gross Kg: {{ number_format($packingList->gross_weight, 2) }}</td>
</tr>

<tr>
    <td colspan="5">
        <div style="text-align: center; font-size: 14px; padding: 10px;">
            <i>The deducted Withholding tax amounts remain payable to Signature Trading Limited until TRA Withholding Tax Certificate 
            is presented as proof of payment.</i>
        </div>
    </td>
</tr>


      
        
        </table>



<div class="image-container">
<img src="{{ asset('public/image/stamp.png') }}" alt="Company Stamp" class="stamp" style="width:300px">
    <img src="{{ asset('public/image/dots.png') }}" alt="Dots Icon">
    <img src="{{ asset('public/image/scanner.png') }}" alt="QR Scanner">
</div>


    </div>
</body>
</html>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commercial Invoice</title>
    <style>
               @page {
    size: A4;
    margin: 15mm;
}
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
            padding: 20px;
            border: 1px solid #000;
            box-sizing: border-box;
        }
        @media print {
    button {
        display: none !important;
    margin: 20px auto;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;

    }
}

        .container {
            width: 100%;
        }
        .header, .table-content {
            width: 100%;
        }
        .header td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .invoice-number {
            color: red;
            font-size: 20px;
            font-weight: bold;
        }
        .table-content, .table-content td, .table-content th {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .table-content th {
            background-color: #f2f2f2;
        }
        .bank-details {
            border: 1px solid black;
            padding: 10px;
            margin-top: 5px;
        }
        .total-section {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .total-table th, .total-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }
        .total-kg {
            text-align: left;
            background-color: #fef6db;
            font-style: italic;
        }
        .total-usd {
            text-align: left;
            background-color: #e6f5e6;
            font-style: italic;
        }
        .total-value {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="header">
            <tr>
                <td>
                    <strong>Signature Trading Ltd</strong><br>
                    P.O Box 1506, Plot No. 147<br>
                    Fire Road, Arusha<br>
                    United Republic Of Tanzania<br>
                    <strong>Tel:</strong> +255 272 97 97 97 / 69 666 6606<br>
                    <strong>Email:</strong> sales@signaturetz.com
                </td>
                <td align="right">
                    <strong>Registration No:</strong> 157893564 <br>
                    Tel: +255 272 97 97 97 <br>
                    Tel: +255 69 666 6606 <br>
                    <a href="https://www.signaturetz.com">www.signaturetz.com</a> <br>
                    Email: <a href="mailto:info@signaturetz.com">info@signaturetz.com</a>
                </td>
            </tr>
        </table>

        <h2 class="invoice-title">Commercial Invoice</h2>

        <table class="table-content">
            <tr>
                <th>Invoice No:</th>
                <td>{{ $order->order_no }}</td>
                <th>Invoice Date:</th>
                <td>{{ date('d/m/Y', strtotime($order->date)) }}</td>
            </tr>
            <tr>
                <th>Customer Id</th>
                <td>{{ $order->customer_code }}</td>
                <th>Page</th>
                <td>1.00</td>
            </tr>
        </table>

        <table class="table-content">
            <tr>
                <td colspan="2"><strong>SIGNATURE TRADING LIMITED</strong><br>
                    P.O Box 1506, Plot No. 147, Fire Road <br>
                    Arusha, Tanzania <br>
                    Tel:+255 272 97 97 97 / 69 666 6606 <br>
                    Email: sales@signaturetz.com
                </td>
                <td colspan="2"><strong>TAYBAT ALBAHR</strong><br>
                    UMM AL DOOM STREET <br>
                    MUAITHER P.O BOX - 96393, DOHA, QATAR <br>
                    TEL: +974 31075459
                </td>
            </tr>
            <tr>
                <th>Shipping Mode</th>
                <td>{{ $order->shipping_mode }}</td>
                <th>Shipping Agent</th>
                <td>{{ $order->shipping_agent }}</td>
            </tr>
            <tr>
                <th>Terms of Delivery</th>
                <td>C&F, DOHA, QATAR</td>
                <th>Terms of Payment</th>
                <td>100% after receiving</td>
            </tr>
            <tr>
                <th>Currency</th>
                <td>USD</td>
            </tr>
        </table>

        <table class="table-content">
            <tr>
                <th>Description</th>
                <th>Packaging</th>
                <th>Quantity (kg)</th>
                <th>Price (USD)</th>
                <th>Par</th>
                <th>Total Amount $</th>
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
        </table>

        <table class="table-content">
            <tr>
                <td colspan="6" class="bold">Country of Origin: TANZANIA</td>
            </tr>
            <tr>
                <td colspan="6" class="bold">Country of Export: TANZANIA</td>
            </tr>
        </table>

        

        <div class="bank-details">
            <h3>Bank Details:</h3>
            <p>
                <strong>USD Account Code:</strong> 42710052781 (USD) <br>
                <strong>Swift Code:</strong> NMIBTZTZ Branch Code: 016 <br>
                <strong>Bank Account Owner:</strong> Signature Trading Limited <br>
                <strong>Bank Name:</strong> NMB BANK PLC <br>
                <strong>Bank Address:</strong> NMB Bank - Arusha Market Branch <br>
                P.O BOX 11168, Arusha, Tanzania
            </p>
        </div>
        <table class="total-table">
            <tr>
                <th class="total-kg">TOTAL KG</th>
                <td class="total-value">{{ number_format($total_kg, 2) }}</td>
                <th class="total-usd">TOTAL USD</th>
                <td class="total-value">{{ number_format($total_amount, 2) }}</td>
            </tr>
        </table>
        <div>
            The deducted Withholding tax amounts remain payable to Signature Trading Limited until TRA Withholding Tax Certificate is presented as proof of payment.
       </div>
       <button onclick="window.print()" style="display: block; margin: 10px auto; padding: 5px 10px; font-size: 14px; cursor: pointer;">
    Print
</button>



    </div>
</body>
</html>

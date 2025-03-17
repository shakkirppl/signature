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
    width: 230mm; /* Increased width */
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
            background-color: #f8f2cc;
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

        .highlight { font-weight: bold; }
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

    </style>
</head>
<body>
    <div class="a4-container">
        <table class="packing-list">
            <tr>
                <td class="header" colspan="2">PACKING LIST</td>
                <td colspan="3">
                    <div class="invoice-container">
                        <div class="row-invoice">
                            <strong>Invoice:</strong> <span class="highlight">10055</span> 
                            <strong>Invoice Date:</strong> 07/03/2025
                        </div>
                        <div class="row">
                            <strong>Customer Id:</strong> 15799
                        </div>
                        <div class="row">
                            <strong>Page:</strong> 1.00
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

            <tr class="total-row">
                <td colspan="4" class="" style="text-align: right;">
                <div class="row">
                            Net Weight: {{ number_format($packingList->net_weight, 2) }}
                        </div>
                        <br>
                        <div class="row">
                            Gross Weight:{{ number_format($packingList->gross_weight, 2) }}
                        </div>
                </td>
           
            </tr>

      
        
        </table>
    </div>
</body>
</html>

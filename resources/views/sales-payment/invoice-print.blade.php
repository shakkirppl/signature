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

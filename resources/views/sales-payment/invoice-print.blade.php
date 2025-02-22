<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Print</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 10px; }
        .invoice-box { width: 700px; margin: auto; padding: 10px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; text-align: center; font-size: 11px; }
        th, td { padding: 5px; }
        .text-left { text-align: left; }
        .no-border { border: none; }
        .print-button { margin-top: 10px; text-align: center; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>

<div class="invoice-box">
<div class="col-md-4">
<h5>Signature Trading Ltd  
 P.O Box 1506,Plot No ,147 
 Fire Road , Arusha 
 United Republic Of Tanzania 
</h5>
</div>
Registration no. 157893564
Tel: +255 272 97 97 97 
Tel: +255 69 666 6606
www.signaturetz.com
Email:info@signaturetz.com
    <h2 style="text-align: center; margin-bottom: 5px;">Commercial Invoice</h2>
    <table>
        <tr>
            <td class="text-left no-border" colspan="2">
                <strong>SIGNATURE TRADING LIMITED</strong><br>
                P.O Box 1506, Plot No.147, Fire Road<br>
                Arusha, Tanzania<br>
                Tel: +255 272 97 97 97 / 69 666 6606<br>
                Email: sales@signaturetz.com
            </td>
            <td class="text-left no-border" colspan="2">
                <strong>TAYBAT ALBAHR</strong><br>
                UMM AL DOOM STREET<br>
                MUAITHER<br>
                P.O BOX: 96393, DOHA, QATAR<br>
                TEL: +974 31075459
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <th>INVOICE</th>
            <td>{{ $order->order_no }}</td>
            <th>Invoice Date</th>
            <td>{{ date('d/m/Y', strtotime($order->date)) }}</td>
        </tr>
        <tr>
            <th>Customer ID</th>
            <td>{{ $order->customer_id }}</td>
            <th>Page</th>
            <td>1.00</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Shipping Mode</th>
            <td>{{ $order->shipping_mode }}</td>
            <th>Shipping Agent</th>
            <td>{{ $order->shipping_agent }}</td>
        </tr>
        <tr>
            <th>Terms of Delivery</th>
            <td>C&F, Doha, Qatar</td>
            <th>Terms of Payment</th>
            <td>100% after receiving</td>
        </tr>
        <tr>
            <th>Currency</th>
            <td>USD</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Description</th>
            <th>HS Code</th>
            <th>Packaging</th>
            <th>Quantity (kg)</th>
            <th>Price (USD)</th>
            <th>Par</th>
            <th>Total Amount $</th>
        </tr>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->description }}</td>
                <td>{{ $product->hsn_code }}</td>
                <td>0</td>
                <td>{{ number_format($product->quantity, 2) }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>pcs</td>
                <td>{{ number_format($product->quantity * $product->price, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <th>Country of Origin</th>
            <td>Tanzania</td>
            <th>Country of Export</th>
            <td>Tanzania</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Total</th>
            <td colspan="6">{{ number_format($total_amount, 2) }}</td>
        </tr>
        <tr>
            <th>Subtotal</th>
            <td colspan="6">{{ number_format($total_amount, 2) }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Bank Details</th>
            <td colspan="6">
                <strong>USD Account Code:</strong> 42710052781 (USD)<br>
                <strong>Swift Code:</strong> NMBITZTZ | Branch Code: 016<br>
                <strong>Bank Account Owner:</strong> Signature Trading Limited<br>
                <strong>Bank Name:</strong> NMB BANK PLC<br>
                <strong>Bank Address:</strong> NMB Bank - Arusha Market Branch<br>
                P.O Box: 11168, Arusha, Tanzania
            </td>
        </tr>
        <tr>
            <th>Total KG</th>
            <td colspan="6">{{ number_format($total_kg, 2) }}</td>
        </tr>
        <tr>
            <th>Total USD</th>
            <td colspan="6">{{ number_format($total_amount, 2) }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="text-left no-border" colspan="2">
                <strong>Signature Trading Ltd</strong><br>
                Registration No. 157893564<br>
                P.O Box 1506, Plot No. 147<br>
                Fire Road, Arusha<br>
                United Republic of Tanzania<br>
                Tel: +255 272 97 97 97 / +255 69 666 6606<br>
                Website: www.signaturetz.com<br>
                Email: info@signaturetz.com
            </td>
        </tr>
    </table>

    <div class="print-button">
        <button onclick="window.print()">Print Invoice</button>
    </div>
</div>

</body>
</html>

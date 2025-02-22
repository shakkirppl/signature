<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Print</title>
    <style>
        @page {
            size: A4; /* Ensures the page is A4 */
            margin: 0; /* Removes default margins */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            margin: 0;
            padding: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .invoice-box {
            width: 190mm; /* Ensures content fits inside A4 */
            height: 277mm; /* Prevents content overflow */
            padding: 10mm; /* Small padding for readability */
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
            font-size: 9px;
        }

        th, td {
            padding: 5px;
        }

        .text-left { text-align: left; }
        .no-border { border: none; }
        .print-button { margin-top: 5px; text-align: center; }

        @media print {
            .print-button {
                display: none;
            }
            body, .container {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            gap: 5px;
        }

        .company-details, .registration-details {
            font-size: 10px;
            line-height: 1.2;
        }

        h2, h5 {
            margin: 3px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice-box">
        <!-- Header Section -->
        <div class="invoice-header">
            <div class="company-details">
                <h5><strong>Signature Trading Ltd</strong></h5>
                <p>P.O Box 1506, Plot No 147 <br>
                   Fire Road, Arusha <br>
                   United Republic Of Tanzania</p>
            </div>
            <div class="registration-details text-end">
                <h5><strong>Reg. No. 157893564</strong></h5>
                <p>Tel: +255 272 97 97 97 <br>
                   Tel: +255 69 666 6606 <br>
                   <a href="https://www.signaturetz.com">www.signaturetz.com</a> <br>
                   Email: <a href="mailto:info@signaturetz.com">info@signaturetz.com</a></p>
            </div>
        </div>

        <!-- Invoice Title -->
        <h2>Commercial Invoice</h2>

        <!-- Invoice Details -->
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
                <td>1</td>
            </tr>
        </table>

        <!-- Shipping Details -->
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

        <!-- Product Details -->
        <table>
            <tr>
                <th>Description</th>
                <th>HS Code</th>
                <th>Quantity (kg)</th>
                <th>Price (USD)</th>
                <th>Total Amount $</th>
            </tr>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->hsn_code }}</td>
                    <td>{{ number_format($product->quantity, 2) }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ number_format($product->quantity * $product->price, 2) }}</td>
                </tr>
            @endforeach
        </table>

        <!-- Country & Total -->
        <table>
            <tr>
                <th>Country of Origin</th>
                <td>Tanzania</td>
                <th>Country of Export</th>
                <td>Tanzania</td>
            </tr>
        </table>

        <!-- Payment Summary -->
        <table>
            <tr>
                <th>Total</th>
                <td>{{ number_format($total_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Subtotal</th>
                <td>{{ number_format($total_amount, 2) }}</td>
            </tr>
        </table>

        <!-- Bank Details -->
        <table>
            <tr>
                <th>Bank Details</th>
                <td>
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
                <td>{{ number_format($total_kg, 2) }}</td>
            </tr>
            <tr>
                <th>Total USD</th>
                <td>{{ number_format($total_amount, 2) }}</td>
            </tr>
        </table>

        <!-- Footer -->
        <table>
            <tr>
                <td class="text-left no-border">
                    <strong>Signature Trading Ltd</strong><br>
                    Registration No. 157893564<br>
                    P.O Box 1506, Plot No. 147, Fire Road, Arusha, Tanzania<br>
                    Tel: +255 272 97 97 97 / +255 69 666 6606<br>
                    Website: www.signaturetz.com | Email: info@signaturetz.com
                </td>
            </tr>
        </table>

        <!-- Print Button -->
        <div class="print-button">
            <button onclick="window.print()">Print Invoice</button>
        </div>
    </div>
</div>

</body>
</html>
